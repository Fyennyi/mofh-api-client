<?php

namespace Fyennyi\MofhApi\DTO\System;

final readonly class ApiVersion
{
    public function __construct(
        public string $version,
        public string $environment = 'production'
    ) {}
}
