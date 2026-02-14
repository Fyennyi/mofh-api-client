<?php

namespace Fyennyi\MofhApi\Dto\Account;

final readonly class SuspensionInfo
{
    public function __construct(
        public string $username,
        public string $reason,
        public \DateTimeImmutable $suspendedAt
    ) {}
}
