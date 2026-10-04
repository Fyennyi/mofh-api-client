<?php

declare(strict_types=1);

namespace Tests\Unit\Repository;

use Fyennyi\MofhApi\Contract\TransportInterface;
use Fyennyi\MofhApi\Dto\Support\TicketReply;
use Fyennyi\MofhApi\Exception\MofhException;
use Fyennyi\MofhApi\Repository\SupportRepository;
use PHPUnit\Framework\TestCase;

class SupportRepositoryTest extends TestCase
{
    public function testCreateTicketReturnsId() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('request')
            ->willReturn('SUCCESS : 123456');

        $repo = new SupportRepository($transport, 'user', 'key');
        $this->assertSame(123456, $repo->createTicket('client1', 'Subj', 'Msg', 'd.com'));
    }

    public function testCreateTicketThrowsOnFailure() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())->method('request')->willReturn('ERROR : Failed');

        $repo = new SupportRepository($transport, 'user', 'key');

        $this->expectException(MofhException::class);
        $this->expectExceptionMessage('Ticket creation failed: ERROR : Failed');
        $repo->createTicket('client1', 'Subj', 'Msg', 'd.com');
    }

    public function testReplyReturnsTrueOnSuccess() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())->method('request')->willReturn('SUCCESS : Ticket Replied');

        $repo = new SupportRepository($transport, 'user', 'key');
        $reply = new TicketReply(123, 'Message');

        $this->assertTrue($repo->reply($reply));
    }

    public function testReplyThrowsOnFailure() : void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())->method('request')->willReturn('FAILED : error');

        $repo = new SupportRepository($transport, 'user', 'key');
        $reply = new TicketReply(123, 'Message');

        $this->expectException(MofhException::class);
        $this->expectExceptionMessage('Failed to reply to ticket #123: FAILED : error');
        $repo->reply($reply);
    }
}
