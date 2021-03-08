<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class CreateCategoryErrorException extends Exception
{
    public function report()
    {
        Log::debug('Error on creating a category!');
    }
}
