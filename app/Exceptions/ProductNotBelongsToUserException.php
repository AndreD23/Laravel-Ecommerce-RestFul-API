<?php

namespace App\Exceptions;

use Exception;

class ProductNotBelongsToUserException extends Exception
{
    public function render()
    {
        return [
            'errors' => 'Product not belongs to user'
        ];
    }
}
