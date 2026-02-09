<?php

namespace Fyennyi\MofhApi\Contract\Repository;

use Fyennyi\MofhApi\DTO\System\Package;

interface SystemRepositoryInterface
{
    /**
     * Retrieves a list of available hosting packages.
     *
     * @return Package[]
     */
    public function getPackages(): array;

    /**
     * Retrieves the MOFH API version.
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Retrieves the CNAME validation token for a domain.
     *
     * @param string $domain
     * @return string MD5 hash token
     */
    public function getCnameToken(string $domain): string;
}
