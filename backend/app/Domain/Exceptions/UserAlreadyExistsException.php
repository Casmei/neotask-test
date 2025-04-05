<?php

namespace App\Domain\Exceptions;

use Exception;

class UserAlreadyExistsException extends Exception
{
    protected $message = "Já existe um usuário com esse e-mail.";
}
