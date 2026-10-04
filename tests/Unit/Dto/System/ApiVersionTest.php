<?php

declare(strict_types=1);

namespace Tests\Unit\Dto\System;

use Fyennyi\MofhApi\Dto\System\ApiVersion;
use PHPUnit\Framework\TestCase;

class ApiVersionTest extends TestCase
{
    public function testProperties() : void
    {
        $apiVersion = new ApiVersion('v1.2.3');

        $this->assertSame('v1.2.3', $apiVersion->version);
        $this->assertSame('production', $apiVersion->environment);
    }
}
