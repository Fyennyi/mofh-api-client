<?php

namespace Fyennyi\MofhApi\Repository;

use Fyennyi\MofhApi\Contract\Repository\SupportRepositoryInterface;
use Fyennyi\MofhApi\Contract\TransportInterface;
use Fyennyi\MofhApi\DTO\Support\TicketReply;
use Fyennyi\MofhApi\Exception\MofhException;

final class SupportRepository implements SupportRepositoryInterface
{
    public function __construct(
        private TransportInterface $transport,
        private string $apiUser,
        private string $apiKey
    ) {}

    public function createTicket(string $clientUsername, string $subject, string $message, string $domain): int
    {
        $response = $this->transport->request('POST', 'supportnewticket.php', [
            'api_user' => $this->apiUser,
            'api_key' => $this->apiKey,
            'clientusername' => $clientUsername,
            'subject' => $subject,
            'comments' => $message,
            'domain_name' => $domain,
            'ipaddress' => '127.0.0.1' // Should be passed from request context
        ], 'text');

        // Response format: "SUCCESS : 123456"
        if (!str_contains($response, 'SUCCESS')) {
            throw new MofhException("Ticket creation failed: $response");
        }

        $parts = explode(':', $response);
        return (int)trim($parts[1] ?? '0');
    }

    /**
     * @inheritDoc
     */
    public function reply(TicketReply $reply): bool
    {
        $response = $this->transport->request(
            'POST', 
            'supportreplyticket.php', 
            $reply->toArray($this->apiUser, $this->apiKey), 
            'text'
        );

        /**
         * MOFH typically returns "SUCCESS : Ticket Replied" or similar string.
         * If the string doesn't contain SUCCESS, we treat it as an API-level error.
         */
        if (!str_contains(strtoupper($response), 'SUCCESS')) {
            throw new MofhException("Failed to reply to ticket #{$reply->ticketId}: " . $response);
        }

        return true;
    }
}
