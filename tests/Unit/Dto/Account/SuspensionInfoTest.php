<?php

declare(strict_types=1);

namespace Tests\Unit\Dto\Account;

use Fyennyi\MofhApi\Dto\Account\SuspensionInfo;
use PHPUnit\Framework\TestCase;

class SuspensionInfoTest extends TestCase
{
    public function testProperties() : void
    {
        $date = new \DateTimeImmutable('2026-10-04');
        $info = new SuspensionInfo('b12_12345', 'Abuse', $date);

        $this->assertSame('b12_12345', $info->username);
        $this->assertSame('Abuse', $info->reason);
        $this->assertSame($date, $info->suspendedAt);
    }
}
