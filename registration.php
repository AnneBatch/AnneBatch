<?php

/**
 * This is a registration script
 * @author Anne Batch
 * @copyright 2021
 */

declare(strict_types = 1);

class SignUp extends DatabaseConnection
{
    /**
     * Database Connection
     * @var PDO
     */
    private $database;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $passwordHash;

    /**
     * @var string
     */
    private $signUpPage;
    
    /**
     * @param string $name Assign the username
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $email Assign the email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param string $password & $confirm Assign password and password confirmation
     */
    public function setPassword(string $password, string $confirm)
    {
        if ($password === $confirm) {
            $options = [
                'cost' => 12,
            ];
            $this->passwordHash = password_hash($password, PASSWORD_BCRYPT, $options);
        }
        else {
            $this->returnWithError('<div class="alert alert-danger" role="alert">Passwords does not match!</div>');
        }
    }

    /**
     * @param $signUpPage Redirect Page
     */
    public function signUpPage(string $signUpPage)
    {
        $this->signUpPage = $signUpPage;
    }

    /**
     * @param $errorMessage Notification Message
     */
    private function returnWithError(string $errorMessage)
    {
        $_SESSION['error'] = $errorMessage;
        header('Location: ' . $this->signUpPage);
        exit();
    }

    /**
     * Checks whether a name exists
     */
    public function existingName(): void
    {
        $statement = $this->database->prepare("SELECT 1 FROM users WHERE username = :username");
        $statement->execute([
            ":username" => $this->name
        ]);
        $exists = $statement->fetchColumn();

        if(!$exists) {
            $this->returnWithError('<div class="alert alert-danger" role="alert">Name already exists!</div>');
        }
    }

    /**
     * Checks whether an email exists
     */
    public function existingEmail(): void
    {
        $statement = $this->database->prepare("SELECT email FROM users WHERE email = :email");
        $statement->execute([
            ":email" => $this->email
        ]);

        $result = $statement->rowCount();

        if($result != 0) {
            $this->returnWithError('<div class="alert alert-danger" role="alert">Email already exists!</div>');
        }
    }

    /**
     * Checks if the fields have been filled
     */
    public function isDefinedFields(): void
    {
        if(!$this->name || !$this->email) {
            $this->returnWithError('<div class="alert alert-danger" role="alert">Please, fill out all the fields!</div>');
        }
    }

    /**
     * Checks if the username is less than 3 or greater than 50
     */
    public function ValidateNameLength(): void
    {
        if(mb_strlen($this->name) < 3) {
            $this->returnWithError('<div class="alert alert-danger" role="alert">Can only use 3 or more characters as name!</div>');
        }

        if(mb_strlen($this->name) > 50) {
            $this->returnWithError('<div class="alert alert-danger" role="alert">Can only use a maximum of 18 characters as name!</div>');
        }
    }

    /**
     * Checks if the email is semantically correct
     */
    public function ValidateEmail(): void
    {
        if(!filter_input(INPUT_POST, $this->email, FILTER_VALIDATE_EMAIL)) {
            $this->returnWithError('<div class="alert alert-danger" role="alert">Incorrect email!</div>');
        }
    }
    
    /**
     * Checks if the username matches the regular expression
     * The purpose of regular expression is to prevent JavaScript attacks
     * The user will not be able to use certain characters, such as <> \/
     */
    public function addUser(): void
    {
        if(preg_match("/^[\w&.\-]*$/", $this->name)) {
                $statement = $this->database->prepare("INSERT INTO users VALUES(?, ?, ?, ?, ?, ?, ?)");
                $statement->execute([
                    $this->name,
                    $this->email,
                    $this->passwordHash,
                    $_SERVER['REMOTE_ADDR'],
                    $_SERVER['HTTP_CLIENT_IP'],
                    $_SERVER['HTTP_X_FORWARDED_FOR'],
                    $_SERVER['HTTP_USER_AGENT']
                ]);
                if($statement) {
                    $this->returnWithError('<div class="alert alert-success" role="alert">Registered successfully!<br>Login now</div>');
                }
            }  
        else {
            $this->returnWithError('<div class="alert alert-danger" role="alert">Can only use letters and numbers as name!</div>');
        }
    }
}

?>
