<?php
declare(strict_types=1);

namespace App\Factories\ApiKey;

interface ApiKeyFactoryInterface
{
    /**
     * Default API Key length
     *
     * @var int
     */
    public const API_KEY_LENGTH = 16;

    /**
     * Create API key.
     *
     * @param null|int $length
     *
     * @return string
     */
    public function create(?int $length = null): string;
}
