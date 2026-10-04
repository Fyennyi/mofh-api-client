<?php

namespace Fyennyi\MofhApi\Repository;

use Fyennyi\MofhApi\Contract\Repository\SystemRepositoryInterface;
use Fyennyi\MofhApi\Contract\TransportInterface;
use Fyennyi\MofhApi\Dto\System\Package;

final class SystemRepository implements SystemRepositoryInterface
{
    public function __construct(
        private TransportInterface $transport,
        private string $apiUser,
        private string $apiKey
    ) {}

    public function getPackages() : array
    {
        // Documentation states that the XML endpoint is broken for this specific call,
        // so we enforce JSON format here.
        // Endpoint: listpkgs.php
        $response = $this->transport->request('GET', 'listpkgs.php', [], 'json');

        if (! is_array($response) || ! isset($response['package']) || ! is_array($response['package'])) {
            return [];
        }

        /** @var array<array<string, int|string|null>> $packages */
        $packages = $response['package'];

        // Mapping raw array to DTO collection using array_map
        return array_map(
            fn (array $pkgData) => Package::fromArray($pkgData),
            $packages
        );
    }

    public function getVersion() : string
    {
        $response = $this->transport->request('GET', 'version.php', [], 'json');

        if (is_array($response) && isset($response['version']) && is_scalar($response['version'])) {
            return (string)$response['version'];
        }

        return 'unknown';
    }

    public function getCnameToken(string $domain) : string
    {
        $response = $this->transport->request('POST', 'getcname.php', [
            'api_user' => $this->apiUser, // Required by some system endpoints
            'api_key'  => $this->apiKey,  // Passed from Client/Connection
            'domain_name' => $domain
        ], 'text');

        return trim(is_scalar($response) ? (string)$response : '');
    }
}
