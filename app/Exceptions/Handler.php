<?php
declare(strict_types=1);

namespace App\Exceptions;

use EoneoPay\Framework\Exceptions\ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class Handler extends ExceptionHandler
{
    /**
     * Initiate the list of exceptions to suppress from the report method
     *
     * @return string[]
     */
    protected function getNonReportableExceptions(): array
    {
        return [
            AuthorizationException::class,
            HttpException::class,
            ModelNotFoundException::class,
            ValidationException::class
        ];
    }
}
