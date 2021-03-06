<?php

namespace App\Application\Auth\Exception;

use Exception;

class TokenNaoEncontradoException extends Exception
{
    public static function execute(): self
    {
        return new self('Token não encontrado');
    }
}
