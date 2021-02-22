<?php

session_start();

require_once('database.php');
require_once('registration.php');

$users = new SignUp('localhost', 'database', 'root', 'password');
$users->setName($_POST['username']);
$users->setEmail($_POST['email']);
$users->signUpPage('signup');
$users->setPassword($_POST['password'], $_POST['password2']);
$users->existingName();
$users->existingEmail();
$users->isDefinedFields();
$users->ValidateNameLength();
$users->ValidateEmail();
$users->addUser();
