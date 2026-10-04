<?php

declare(strict_types=1);

namespace Tests\Unit\Dto\System;

use Fyennyi\MofhApi\Dto\System\Package;
use PHPUnit\Framework\TestCase;

class PackageTest extends TestCase
{
    public function testFromArray() : void
    {
        $data = [
            'name' => 'Premium Plan',
            'BWLIMIT' => '10000',
            'QUOTA' => '5000',
            'MAXFTP' => '10',
            'MAXSQL' => '5',
            'MAXSUB' => '20',
            'MAXPARK' => '15',
            'MAXADDON' => '10',
            'HASSHELL' => 'Y',
            'CGI' => 'n',
        ];

        $package = Package::fromArray($data);

        $this->assertSame('Premium Plan', $package->name);
        $this->assertSame('10000', $package->bandwidthLimit);
        $this->assertSame('5000', $package->diskQuota);
        $this->assertSame(10, $package->maxFtp);
        $this->assertSame(5, $package->maxSql);
        $this->assertSame(20, $package->maxSubdomains);
        $this->assertSame(15, $package->maxParkedDomains);
        $this->assertSame(10, $package->maxAddonDomains);
        $this->assertTrue($package->hasShellAccess);
        $this->assertFalse($package->hasCgi);
    }
}
