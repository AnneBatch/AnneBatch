<?php

namespace Myapp\Data;

class Password
{
    const ALGO = PASSWORD_BCRYPT;

    const OPTIONS = [
        'cost' => 12,
    ];

    public static function hash(string $password): string
    {
        if ($password === '') {
            throw new \RuntimeException('Password cannot be empty.');
        }

        return password_hash($password, self::ALGO, self::OPTIONS);
    }
}
