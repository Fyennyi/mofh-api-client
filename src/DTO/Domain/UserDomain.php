<?php

namespace Fyennyi\MofhApi\DTO\Domain;

final readonly class UserDomain
{
    public function __construct(
        public string $domain,
        public string $username,
        public \DateTimeImmutable $detectedAt = new \DateTimeImmutable()
    ) {}
}
