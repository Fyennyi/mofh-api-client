<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Fyennyi\MofhApi\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

class ValidationExceptionTest extends TestCase
{
    public function testInstantiation() : void
    {
        $exception = new ValidationException('Validation failed');
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertSame('Validation failed', $exception->getMessage());
    }
}
