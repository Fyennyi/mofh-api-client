<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Fyennyi\MofhApi\Exception\AuthenticationException;
use PHPUnit\Framework\TestCase;

class AuthenticationExceptionTest extends TestCase
{
    public function testInstantiation() : void
    {
        $exception = new AuthenticationException('Auth failed');
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertSame('Auth failed', $exception->getMessage());
    }
}
