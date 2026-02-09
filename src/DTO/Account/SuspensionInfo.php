<?php

namespace Fyennyi\MofhApi\DTO\Account;

final readonly class SuspensionInfo
{
    public function __construct(
        public string $username,
        public string $reason,
        public \DateTimeImmutable $suspendedAt
    ) {}
}
