<?php
declare(strict_types=1);

namespace App\Factories\ApiKey;

use EoneoPay\Utils\Generator;

final class ApiKeyFactory implements ApiKeyFactoryInterface
{
    /**
     * Create API key.
     *
     * @param null|int $length
     *
     * @return string
     */
    public function create(?int $length = null): string
    {
        return (new Generator())->randomString($length ?? self::API_KEY_LENGTH);
    }
}
