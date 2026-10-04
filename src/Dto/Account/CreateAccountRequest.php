<?php

namespace Fyennyi\MofhApi\Dto\Account;

final readonly class CreateAccountRequest
{
    public function __construct(
        public string $username,
        public string $password,
        public string $contactEmail,
        public string $domain,
        public string $plan
    ) {}

    /**
     * @return array<string, string>
     */
    public function toArray() : array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'contactemail' => $this->contactEmail,
            'domain' => $this->domain,
            'plan' => $this->plan,
        ];
    }
}
