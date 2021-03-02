<?php

declare(strict_types=1);

namespace Myapp\Validator;

class Validator
{
    public function validateEmail(string $email): string
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function findUserByEmail(string $email): string
    {
        $statement = $this->databaseConnection->prepare("SELECT 1 FROM users WHERE email = :email");
        $statement->execute([
            ":email" => $this->email
        ]);

        $exists = $statement->fetchColumn();

        return $exists;
    }

    public function findUserByUsername(string $name): string
    {
        $statement = $this->databaseConnection->prepare("SELECT 1 FROM users WHERE username = :username");
        $statement->execute([
            ":username" => $this->name
        ]);

        $exists = $statement->fetchColumn();

        return $exists;
    }

    public function validateNameLength(string $name): bool
    {
        return mb_strlen($name) < 3 || mb_strlen($name) > 50;
    }
}
