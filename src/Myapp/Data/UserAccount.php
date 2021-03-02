<?php

declare(strict_types=1);

namespace Myapp\Data;

class UserAccount
{ 
    private \PDO $databaseConnection;
    private string $name;
    private string $email;
    private string $passwordHash;

    public function __construct(public Validator $validator) 
    {

    }


    public function setUsername(string $name): void
    {
        if (!$this->validator->findUserByUsername($name)) {
            throw new SignUpValidationError('Name already exists');
        }

        if (!$this->validator->validateNameLength($name)) {
            throw new SignUpValidationError('Name must be greater than 3 or less than 50');
        }
        $this->name = $name;
    }

    public function getUsername(): string
    {
        return $this->name;
    }

    public function setEmail(string $email): void
    {
        if (!$this->validator->validateEmail($email)) {
            throw new \RuntimeException('Incorrect email!');
        }

        if (!$this->validator->findUserByEmail($email)) {
            throw new SignUpValidationError('Email already exists');
        }

        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password, string $confirm): void
    {
        if ($password === $confirm) {
            $this->passwordHash = Password::hash($password);
        }
        else {
            throw new \RuntimeException('Passwords do not match!');
        }
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}
