<?php

declare(strict_types=1);

namespace Myapp\Data;

class SignUp
{

    public function __construct(private PDO $databaseConnection)
    {
    }

    public function addUser(UserAccount $user): void
    {
        $statement = $this->databaseConnection->prepare("INSERT INTO users VALUES(?, ?, ?, ?, ?, ?, ?)");
        $statement->execute([
            $user->getUsername(),
            $user->getEmail(),
            $user->getPasswordHash(),
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_CLIENT_IP'],
            $_SERVER['HTTP_X_FORWARDED_FOR'],
            $_SERVER['HTTP_USER_AGENT']
        ]);
    }
}
