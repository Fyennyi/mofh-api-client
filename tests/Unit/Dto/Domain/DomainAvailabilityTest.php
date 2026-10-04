<?php

declare(strict_types=1);

namespace Tests\Unit\Dto\Domain;

use Fyennyi\MofhApi\Dto\Domain\DomainAvailability;
use PHPUnit\Framework\TestCase;

class DomainAvailabilityTest extends TestCase
{
    public function testProperties() : void
    {
        $availability = new DomainAvailability('test.com', true, 'Available');

        $this->assertSame('test.com', $availability->domain);
        $this->assertTrue($availability->isAvailable);
        $this->assertSame('Available', $availability->message);
    }
}
