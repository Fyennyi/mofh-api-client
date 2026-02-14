<?php

namespace Fyennyi\MofhApi\Dto\Account;

final readonly class AccountResponse
{
    public function __construct(
        public string $vPanelUsername,
        public string $statusMessage,
        public array $nameservers
    ) {}

    public static function fromXml(\SimpleXMLElement $xml): self
    {
        $options = $xml->result->options;
        $ns = [];
        if (isset($options->nameserver)) $ns[] = (string)$options->nameserver;
        if (isset($options->nameserver2)) $ns[] = (string)$options->nameserver2;

        return new self(
            vPanelUsername: (string)$options->vpusername,
            statusMessage: (string)$xml->result->statusmsg,
            nameservers: $ns
        );
    }
}
