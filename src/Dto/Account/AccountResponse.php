<?php

namespace Fyennyi\MofhApi\Dto\Account;

final readonly class AccountResponse
{
    /**
     * @param array<int, string> $nameservers
     */
    public function __construct(
        public string $vPanelUsername,
        public string $statusMessage,
        public array $nameservers
    ) {}

    public static function fromXml(\SimpleXMLElement $xml) : self
    {
        /** @var \SimpleXMLElement $result */
        $result = $xml->result;
        /** @var \SimpleXMLElement $options */
        $options = $result->options;

        $ns = [];
        if (isset($options->nameserver)) {
            $ns[] = (string)$options->nameserver;
        }
        if (isset($options->nameserver2)) {
            $ns[] = (string)$options->nameserver2;
        }

        return new self(
            vPanelUsername: (string)$options->vpusername,
            statusMessage: (string)$result->statusmsg,
            nameservers: $ns
        );
    }
}
