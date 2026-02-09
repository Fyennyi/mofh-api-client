<?php

namespace Fyennyi\MofhApi\Contract\Repository;

interface DomainRepositoryInterface
{
    /**
     * @param string $domain
     * @return bool
     */
    public function checkAvailability(string $domain): bool;

    /**
     * @param string $username
     * @return array
     */
    public function getUserDomains(string $username): array;

    /**
     * Retrieves account info by domain name.
     *
     * @param string $domain
     * @return array|null Returns [status, domain, path, username] or null
     */
    public function getUserByDomain(string $domain): ?array;
}
