<?php

namespace Fyennyi\MofhApi\Transport;

use Fyennyi\MofhApi\Exception\MofhException;

final class ResponseParser
{
    /**
     * Normalizes and parses the API response based on format.
     * * @throws MofhException
     */
    public static function parse(string $content, string $format): mixed
    {
        return match ($format) {
            'json' => self::parseJson($content),
            'xml'  => self::parseXml($content),
            default => $content,
        };
    }

    private static function parseJson(string $content): array
    {
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new MofhException("Failed to decode JSON: " . json_last_error_msg());
        }
        return $data;
    }

    private static function parseXml(string $content): \SimpleXMLElement
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);
        if (!$xml) {
            throw new MofhException("Failed to parse XML response");
        }
        return $xml;
    }
}
