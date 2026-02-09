<?php

namespace Fyennyi\MofhApi\Transport;

use Fyennyi\MofhApi\Connection;
use Fyennyi\MofhApi\Contract\TransportInterface;
use Fyennyi\MofhApi\Exception\AuthenticationException;
use Fyennyi\MofhApi\Exception\MofhException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;

final class HttpTransport implements TransportInterface
{
    private const BASE_URL_JSON = 'https://panel.myownfreehost.net/json-api/';
    private const BASE_URL_XML = 'https://panel.myownfreehost.net/xml-api/';

    public function __construct(
        private Connection $connection,
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private LoggerInterface $logger
    ) {}

    public function request(string $method, string $endpoint, array $data = [], string $format = 'json'): mixed
    {
        // Select base URL based on requested format, as MOFH splits endpoints
        $baseUrl = ($format === 'xml') ? self::BASE_URL_XML : self::BASE_URL_JSON;
        $uri = $baseUrl . $endpoint;

        $this->logger->info("MOFH Request: $method $uri", ['format' => $format]);

        // MOFH expects auth in Basic Auth header or POST body depending on endpoint luck.
        // We use Basic Auth as per standard documentation.
        $request = $this->requestFactory->createRequest($method, $uri)
            ->withHeader('Authorization', 'Basic ' . base64_encode(
                $this->connection->getUsername() . ':' . $this->connection->getPassword()
            ));

        if ($method === 'POST') {
            // MOFH strictly uses form-url-encoded, rarely raw JSON body
            $body = http_build_query($data);
            $request = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded');
            $request->getBody()->write($body);
        }

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (\Throwable $e) {
            $this->logger->error("HTTP Error: " . $e->getMessage());
            throw new MofhException("Transport error: " . $e->getMessage(), 0, $e);
        }

        $content = (string)$response->getBody();
        $this->logger->debug("MOFH Response", ['content' => substr($content, 0, 200) . '...']);

        return $this->parseResponse($content, $format);
    }

    private function parseResponse(string $content, string $format): mixed
    {
        if (trim($content) === 'null') {
            return null; // Handle weird null responses
        }

        // MOFH always returns 200 OK, so we must inspect body
        if ($format === 'json') {
            $json = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                 // Fallback: Some "JSON" endpoints return XML on error or specific actions
                if (str_starts_with(trim($content), '<')) {
                    return $this->parseResponse($content, 'xml');
                }
                throw new MofhException("JSON Decode Error: " . json_last_error_msg());
            }
            // Check for error inside JSON structure (result -> status)
            if (isset($json['result'][0]['status']) && (int)$json['result'][0]['status'] === 0) {
                 $msg = $json['result'][0]['statusmsg'] ?? 'Unknown error';
                 throw new MofhException("API Error: $msg");
            }
            return $json;
        }

        if ($format === 'xml') {
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($content);
            if ($xml === false) {
                 throw new MofhException("XML Parse Error");
            }
            // Check XML status
            if (isset($xml->result->status) && (int)$xml->result->status === 0) {
                $msg = (string)$xml->result->statusmsg;
                throw new MofhException("API Error: $msg");
            }
            return $xml;
        }

        // Text format (Ticket system)
        return $content;
    }
}
