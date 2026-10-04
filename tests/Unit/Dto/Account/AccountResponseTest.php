<?php

declare(strict_types=1);

namespace Tests\Unit\Dto\Account;

use Fyennyi\MofhApi\Dto\Account\AccountResponse;
use PHPUnit\Framework\TestCase;

class AccountResponseTest extends TestCase
{
    public function testFromXml() : void
    {
        $xmlString = <<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <acct>
            <result>
                <statusmsg>Account creation successful</statusmsg>
                <options>
                    <vpusername>b12_123456</vpusername>
                    <nameserver>ns1.example.com</nameserver>
                    <nameserver2>ns2.example.com</nameserver2>
                </options>
            </result>
        </acct>
        XML;

        $xml = simplexml_load_string($xmlString);
        $response = AccountResponse::fromXml($xml);

        $this->assertSame('b12_123456', $response->vPanelUsername);
        $this->assertSame('Account creation successful', $response->statusMessage);
        $this->assertSame(['ns1.example.com', 'ns2.example.com'], $response->nameservers);
    }
}
