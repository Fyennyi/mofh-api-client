<?php

namespace Fyennyi\MofhApi\Dto\Domain;

final readonly class DomainAvailability
{
    public function __construct(
        public string $domain,
        public bool $isAvailable,
        public string $message = ''
    ) {}
}
