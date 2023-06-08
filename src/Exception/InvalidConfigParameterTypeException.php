<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class InvalidConfigParameterTypeException extends Exception
{
    public function __construct(string $varName = '', string $expectedType = '', mixed $var = null)
    {
        parent::__construct(sprintf('Parameter "%s" must be type of "%s" got "%s"', $varName, $expectedType, gettype($var)));
    }
}
