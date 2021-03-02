<?php

declare(strict_types=1);

session_start();

$host = 'localhost';
$dbname = 'dbname';
$username = 'root';
$password = '';

try {
    $pdo = new \PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

$http = new HTTPTransport();
$validator = new Validator(); 

$newUser = new UserAccount($validator);

try {
    $newUser->setUsername(filter_input(INPUT_POST, 'name'));
    $newUser->setEmail(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $newUser->setPassword(filter_input(INPUT_POST, 'password'), filter_input(INPUT_POST, 'password2'));
} catch (SignUpValidationError $e) {
    $http->setFlashMessage('error', $e->getMessage());
    $http->redirect('signup');
}

(new Signup($pdo))->addUser($newUser);
$http->setFlashMessage('success', 'Registered succesfully');
$http->redirect('login');
