<?php

namespace Fyennyi\MofhApi\Repository;

use Fyennyi\MofhApi\Contract\Repository\DomainRepositoryInterface;
use Fyennyi\MofhApi\Contract\TransportInterface;

final class DomainRepository implements DomainRepositoryInterface
{
    public function __construct(
        private TransportInterface $transport,
        private string $apiUser, // Required for domain-related calls as per docs
        private string $apiKey
    ) {}

    public function checkAvailability(string $domain): bool
    {
        $response = $this->transport->request('POST', 'checkavailable.php', [
            'api_user' => $this->apiUser,
            'api_key' => $this->apiKey,
            'domain' => $domain
        ], 'json');

        // Docs say returns 1 or 0
        return (string)$response === '1';
    }

    public function getUserDomains(string $username): array
    {
        $response = $this->transport->request('POST', 'getuserdomains.php', [
            'api_user' => $this->apiUser,
            'api_key' => $this->apiKey,
            'username' => $username
        ], 'xml'); // Using XML endpoint for structured data reliability

        // Logic to parse XML array to simple array would go here
        // Simplified for brevity
        return []; 
    }
}
