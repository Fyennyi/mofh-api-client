<?php

declare(strict_types=1);

namespace Tests\Unit;

use Fyennyi\MofhApi\Connection;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function testGetters() : void
    {
        $connection = new Connection('user123', 'pass456');

        $this->assertSame('user123', $connection->getUsername());
        $this->assertSame('pass456', $connection->getPassword());
    }
}
