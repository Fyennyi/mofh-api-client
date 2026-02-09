<?php

namespace Fyennyi\MofhApi\DTO\System;

final readonly class Package
{
    public function __construct(
        public string $name,
        public string $bandwidthLimit, // API might return huge numbers or strings, keeping it string is safer
        public string $diskQuota,
        public int $maxFtp,
        public int $maxSql,
        public int $maxSubdomains,
        public int $maxParkedDomains,
        public int $maxAddonDomains,
        public bool $hasShellAccess,
        public bool $hasCgi
    ) {}

    /**
     * Factory method to create an object from the raw API array.
     *
     * @param array $data Raw data from the API response
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string)($data['name'] ?? 'Unknown'),
            bandwidthLimit: (string)($data['BWLIMIT'] ?? '0'),
            diskQuota: (string)($data['QUOTA'] ?? '0'),
            maxFtp: (int)($data['MAXFTP'] ?? 0),
            maxSql: (int)($data['MAXSQL'] ?? 0),
            maxSubdomains: (int)($data['MAXSUB'] ?? 0),
            maxParkedDomains: (int)($data['MAXPARK'] ?? 0),
            maxAddonDomains: (int)($data['MAXADDON'] ?? 0),
            hasShellAccess: isset($data['HASSHELL']) && strtolower($data['HASSHELL']) === 'y',
            hasCgi: isset($data['CGI']) && strtolower($data['CGI']) === 'y'
        );
    }
}
