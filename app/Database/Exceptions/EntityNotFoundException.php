<?php
declare(strict_types=1);

namespace App\Database\Exceptions;

use EoneoPay\Utils\Exceptions\NotFoundException;

final class EntityNotFoundException extends NotFoundException
{
    /**
     * Get Error code.
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return self::DEFAULT_ERROR_CODE_NOT_FOUND;
    }

    /**
     * Get Error sub-code.
     *
     * @return int
     */
    public function getErrorSubCode(): int
    {
        return self::DEFAULT_ERROR_SUB_CODE;
    }
}
