<?php

declare(strict_types=1);

namespace Tests\Unit\Repository;

use Fyennyi\MofhApi\Contract\TransportInterface;
use Fyennyi\MofhApi\Dto\Domain\UserDomain;
use Fyennyi\MofhApi\Repository\DomainRepository;
use PHPUnit\Framework\TestCase;

class DomainRepositoryTest extends TestCase
{
    public function testCheckAvailabilityReturnsTrue() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('POST', 'checkavailable.php', ['api_user' => 'user', 'api_key' => 'key', 'domain' => 'example.com'], 'json')
            ->willReturn(['1']);

        $repo = new DomainRepository($transport, 'user', 'key');
        $this->assertTrue($repo->checkAvailability('example.com'));
    }

    public function testCheckAvailabilityReturnsFalse() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->willReturn(['0']);

        $repo = new DomainRepository($transport, 'user', 'key');
        $this->assertFalse($repo->checkAvailability('example.com'));
    }

    public function testGetUserDomainsReturnsArray() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $xml = simplexml_load_string('<root><result><item>d1.com</item><item>d2.com</item></result></root>');

        $transport->expects($this->once())
            ->method('request')
            ->with('POST', 'getuserdomains.php', ['api_user' => 'user', 'api_key' => 'key', 'username' => 'u1'], 'xml')
            ->willReturn($xml);

        $repo = new DomainRepository($transport, 'user', 'key');
        $domains = $repo->getUserDomains('u1');

        $this->assertCount(2, $domains);
        $this->assertInstanceOf(UserDomain::class, $domains[0]);
        $this->assertSame('d1.com', $domains[0]->domain);
        $this->assertSame('d2.com', $domains[1]->domain);
    }
    
    public function testGetUserDomainsReturnsEmptyIfNoItems() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $xml = simplexml_load_string('<root><result></result></root>');
        $transport->expects($this->once())->method('request')->willReturn($xml);

        $repo = new DomainRepository($transport, 'user', 'key');
        $domains = $repo->getUserDomains('u1');
        $this->assertCount(0, $domains);
    }

    public function testGetUserByDomainReturnsArray() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('POST', 'getdomainuser.php', ['api_user' => 'user', 'api_key' => 'key', 'domain' => 'd1.com'], 'json')
            ->willReturn(['user' => 'b12_123']);

        $repo = new DomainRepository($transport, 'user', 'key');
        $this->assertSame(['user' => 'b12_123'], $repo->getUserByDomain('d1.com'));
    }

    public function testGetUserByDomainReturnsNullIfNotArray() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())->method('request')->willReturn('not_an_array');

        $repo = new DomainRepository($transport, 'user', 'key');
        $this->assertNull($repo->getUserByDomain('d1.com'));
    }
}
