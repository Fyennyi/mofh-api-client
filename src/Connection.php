<?php

namespace Fyennyi\MofhApi;

final class Connection
{
    public function __construct(
        private string $username,
        private string $password
    ) {}

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
