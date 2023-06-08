<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class InvalidConfigParameterTypeException extends Exception
{
    public function __construct(string $varName = '', string $expectedType = '')
    {
        parent::__construct(sprintf('Parameter "%s" must be type of "%s"', $varName, $expectedType));
    }
}
