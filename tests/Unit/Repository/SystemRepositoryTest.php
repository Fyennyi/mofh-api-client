<?php

declare(strict_types=1);

namespace Tests\Unit\Repository;

use Fyennyi\MofhApi\Contract\TransportInterface;
use Fyennyi\MofhApi\Dto\System\Package;
use Fyennyi\MofhApi\Repository\SystemRepository;
use PHPUnit\Framework\TestCase;

class SystemRepositoryTest extends TestCase
{
    public function testGetPackagesReturnsArrayOfPackages() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $apiData = ['package' => [['name' => 'Plan1']]];
        $transport->expects($this->once())->method('request')->willReturn($apiData);

        $repo = new SystemRepository($transport, 'user', 'key');
        $packages = $repo->getPackages();

        $this->assertCount(1, $packages);
        $this->assertInstanceOf(Package::class, $packages[0]);
        $this->assertSame('Plan1', $packages[0]->name);
    }

    public function testGetPackagesReturnsEmptyArrayIfNoPackageKey() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())->method('request')->willReturn(['other' => 'data']);

        $repo = new SystemRepository($transport, 'user', 'key');
        $this->assertSame([], $repo->getPackages());
    }

    public function testGetVersionReturnsVersion() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())->method('request')->willReturn(['version' => '1.2.3']);

        $repo = new SystemRepository($transport, 'user', 'key');
        $this->assertSame('1.2.3', $repo->getVersion());
    }

    public function testGetCnameTokenReturnsToken() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('POST', 'getcname.php', ['api_user' => 'user', 'api_key' => 'key', 'domain_name' => 'd.com'], 'text')
            ->willReturn(' token_value ');

        $repo = new SystemRepository($transport, 'user', 'key');
        $this->assertSame('token_value', $repo->getCnameToken('d.com'));
    }
}
