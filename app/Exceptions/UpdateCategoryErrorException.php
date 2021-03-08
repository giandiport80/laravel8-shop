<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class UpdateCategoryErrorException extends Exception
{
    public function report()
    {
        Log::debug('Error on updating a category!');
    }
}
