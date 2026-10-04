<?php

declare(strict_types=1);

namespace Tests\Unit\Repository;

use Fyennyi\MofhApi\Contract\TransportInterface;
use Fyennyi\MofhApi\Dto\Account\AccountResponse;
use Fyennyi\MofhApi\Dto\Account\CreateAccountRequest;
use Fyennyi\MofhApi\Repository\AccountRepository;
use PHPUnit\Framework\TestCase;

class AccountRepositoryTest extends TestCase
{
    public function testCreate() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $request = new CreateAccountRequest('u1', 'p1', 'e@e.com', 'd.com', 'plan1');

        $xml = simplexml_load_string('<acct><result><options><vpusername>b12_123</vpusername></options><statusmsg>ok</statusmsg></result></acct>');

        $transport->expects($this->once())
            ->method('request')
            ->with('POST', 'createacct.php', $request->toArray(), 'xml')
            ->willReturn($xml);

        $repo = new AccountRepository($transport);
        $response = $repo->create($request);

        $this->assertInstanceOf(AccountResponse::class, $response);
        $this->assertSame('b12_123', $response->vPanelUsername);
    }

    public function testCreateThrowsExceptionOnNonXmlResponse() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $request = new CreateAccountRequest('u1', 'p1', 'e@e.com', 'd.com', 'plan1');

        $transport->expects($this->once())
            ->method('request')
            ->willReturn('not an xml');

        $repo = new AccountRepository($transport);

        $this->expectException(\Fyennyi\MofhApi\Exception\MofhException::class);
        $this->expectExceptionMessage('Expected XML response from createacct.php');
        $repo->create($request);
    }

    public function testSuspend() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('POST', 'suspendacct.php', ['user' => 'u1', 'reason' => 'bad'], 'json');

        $repo = new AccountRepository($transport);
        $this->assertTrue($repo->suspend('u1', 'bad'));
    }

    public function testUnsuspend() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('POST', 'unsuspendacct.php', ['user' => 'u1'], 'json');

        $repo = new AccountRepository($transport);
        $this->assertTrue($repo->unsuspend('u1'));
    }

    public function testRemove() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('POST', 'removeacct.php', ['user' => 'u1'], 'json');

        $repo = new AccountRepository($transport);
        $this->assertTrue($repo->remove('u1'));
    }

    public function testChangePassword() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('POST', 'passwd.php', ['user' => 'u1', 'pass' => 'newp'], 'json');

        $repo = new AccountRepository($transport);
        $this->assertTrue($repo->changePassword('u1', 'newp'));
    }

    public function testChangePackage() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->with('POST', 'changepackage.php', ['user' => 'u1', 'pkg' => 'newpkg'], 'xml');

        $repo = new AccountRepository($transport);
        $this->assertTrue($repo->changePackage('u1', 'NewPkg'));
    }
}
