<?php

declare(strict_types=1);

namespace Myapp\Http;

class HTTPTransport 
{
    public function setFlashMessage(string $title, string $message)
    {
        $_SESSION[$title] = '<p>'.$message.'</p>';
    }

    public function redirect(string $url) 
    {
        header('Location: ' . $url);
        exit(); 
    }
}
