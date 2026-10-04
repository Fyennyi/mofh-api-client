<?php

declare(strict_types=1);

namespace Tests\Unit\Dto\Support;

use Fyennyi\MofhApi\Dto\Support\TicketReply;
use PHPUnit\Framework\TestCase;

class TicketReplyTest extends TestCase
{
    public function testPropertiesAndToArray() : void
    {
        $reply = new TicketReply(12345, 'Help message');

        $this->assertSame(12345, $reply->ticketId);
        $this->assertSame('Help message', $reply->message);
        $this->assertSame('open', $reply->status);

        $expected = [
            'api_user' => 'user1',
            'api_key' => 'key1',
            'ticket_id' => 12345,
            'replier' => 'admin',
            'content' => 'Help message',
        ];

        $this->assertSame($expected, $reply->toArray('user1', 'key1'));
    }
}
