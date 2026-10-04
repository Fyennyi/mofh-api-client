<?php

declare(strict_types=1);

namespace Tests\Unit\Dto\Domain;

use Fyennyi\MofhApi\Dto\Domain\UserDomain;
use PHPUnit\Framework\TestCase;

class UserDomainTest extends TestCase
{
    public function testProperties() : void
    {
        $date = new \DateTimeImmutable('2026-10-04');
        $userDomain = new UserDomain('example.com', 'b12_test', $date);

        $this->assertSame('example.com', $userDomain->domain);
        $this->assertSame('b12_test', $userDomain->username);
        $this->assertSame($date, $userDomain->detectedAt);
    }
}
