<?php

namespace Fyennyi\MofhApi\Repository;

use Fyennyi\MofhApi\Contract\Repository\SystemRepositoryInterface;
use Fyennyi\MofhApi\Contract\TransportInterface;
use Fyennyi\MofhApi\DTO\System\Package;
use Fyennyi\MofhApi\Exception\MofhException;

final class SystemRepository implements SystemRepositoryInterface
{
    public function __construct(
        private TransportInterface $transport,
        private string $apiUser,
        private string $apiKey
    ) {}

    public function getPackages(): array
    {
        // Documentation states that the XML endpoint is broken for this specific call,
        // so we enforce JSON format here.
        // Endpoint: listpkgs.php
        $response = $this->transport->request('GET', 'listpkgs.php', [], 'json');

        if (!isset($response['package']) || !is_array($response['package'])) {
            return [];
        }

        // Mapping raw array to DTO collection using array_map
        return array_map(
            fn(array $pkgData) => Package::fromArray($pkgData),
            $response['package']
        );
    }

    public function getVersion(): string
    {
        $response = $this->transport->request('GET', 'version.php', [], 'json');

        return (string)($response['version'] ?? 'unknown');
    }

    public function getCnameToken(string $domain): string
    {
        $response = $this->transport->request('POST', 'getcname.php', [
            'api_user' => $this->apiUser, // Required by some system endpoints
            'api_key'  => $this->apiKey,  // Passed from Client/Connection
            'domain_name' => $domain
        ], 'text');

        return trim((string)$response);
    }
}
