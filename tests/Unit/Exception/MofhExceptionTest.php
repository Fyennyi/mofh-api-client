<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Fyennyi\MofhApi\Exception\MofhException;
use PHPUnit\Framework\TestCase;

class MofhExceptionTest extends TestCase
{
    public function testInstantiation() : void
    {
        $exception = new MofhException('API Error');
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertSame('API Error', $exception->getMessage());
    }
}
