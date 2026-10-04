<?php

declare(strict_types=1);

namespace Tests\Unit\Dto\Account;

use Fyennyi\MofhApi\Dto\Account\CreateAccountRequest;
use PHPUnit\Framework\TestCase;

class CreateAccountRequestTest extends TestCase
{
    public function testToArray() : void
    {
        $request = new CreateAccountRequest(
            'testuser',
            'testpass',
            'user@example.com',
            'test.example.com',
            'TestPlan'
        );

        $expected = [
            'username' => 'testuser',
            'password' => 'testpass',
            'contactemail' => 'user@example.com',
            'domain' => 'test.example.com',
            'plan' => 'TestPlan',
        ];

        $this->assertSame($expected, $request->toArray());
    }
}
