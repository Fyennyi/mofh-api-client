<?php

namespace Fyennyi\MofhApi\Dto\System;

final readonly class ApiVersion
{
    public function __construct(
        public string $version,
        public string $environment = 'production'
    ) {}
}
