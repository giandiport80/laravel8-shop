<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class CategoryNotFoundErrorException extends Exception
{
    public function report()
    {
        Log::debug('Category not found!');
    }
}
