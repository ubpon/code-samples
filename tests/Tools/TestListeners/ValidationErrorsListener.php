<?php
declare(strict_types=1);

namespace Tests\App\Tools\TestListeners;

use EoneoPay\Utils\Exceptions\ValidationException;
use PHPUnit\Framework\ExceptionWrapper;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;

class ValidationErrorsListener implements TestListener
{
    use TestListenerDefaultImplementation;

    /**
     * Display list of errors if validation exception thrown during test.
     *
     * @param \PHPUnit\Framework\Test $test
     * @param \Throwable $throwable
     * @param float $time
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter) Inherited from PHPUnit
     */
    public function addError(Test $test, \Throwable $throwable, float $time): void
    {
        // Skip if throwable isn't an exception wrapper
        if (($throwable instanceof ExceptionWrapper) === false) {
            return;
        }

        /** @var \PHPUnit\Framework\ExceptionWrapper $throwable */
        $exception = $throwable->getOriginalException();

        // Skip if exception isn't a validation exception
        if ($exception === null || (($exception instanceof ValidationException) === false)) {
            return;
        }

        /** @var \EoneoPay\Utils\Exceptions\ValidationException $exception */
        print \PHP_EOL . 'Validation errors:';
        print \PHP_EOL . \print_r($exception->getErrors(), true);
    }
}
