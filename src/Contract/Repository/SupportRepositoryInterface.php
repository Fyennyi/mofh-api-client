<?php

namespace Fyennyi\MofhApi\Contract\Repository;

use Fyennyi\MofhApi\DTO\Support\TicketReply;

interface SupportRepositoryInterface
{
    /**
     * @param string $clientUsername
     * @param string $subject
     * @param string $message
     * @param string $domain
     * @return int Ticket ID
     */
    public function createTicket(string $clientUsername, string $subject, string $message, string $domain): int;

    /**
     * Adds a reply to an existing support ticket.
     *
     * @param TicketReply $reply
     * @return bool
     */
    public function reply(TicketReply $reply): bool;
}
