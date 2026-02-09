<?php

namespace Fyennyi\MofhApi\DTO\Support;

final readonly class TicketReply
{
    public function __construct(
        public int $ticketId,
        public string $message,
        public string $status = 'open'
    ) {}

    /**
     * Converts DTO to API-compatible array.
     */
    public function toArray(string $apiUser, string $apiKey): array
    {
        return [
            'api_user' => $apiUser,
            'api_key'  => $apiKey,
            'ticket_id' => $this->ticketId,
            'replier'   => 'admin', // Usually required by MOFH legacy core
            'content'   => $this->message
        ];
    }
}
