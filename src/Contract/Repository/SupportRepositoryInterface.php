<?php

namespace Fyennyi\MofhApi\Contract\Repository;

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
}
