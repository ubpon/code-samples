<?php
declare(strict_types=1);

namespace App\Factories\ApiResponse;

use EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface;

interface ApiResponseFactoryInterface
{
    /**
     * Create a formatted api response for given parameters.
     *
     * @param mixed $content
     * @param null|int $statusCode
     * @param null|mixed[] $headers
     *
     * @return \EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface
     */
    public function create($content, ?int $statusCode = null, ?array $headers = null): FormattedApiResponseInterface;

    /**
     * Create an empty formatted api response.
     *
     * @param null|mixed[] $headers
     *
     * @return \EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface
     */
    public function createEmpty(?array $headers = null): FormattedApiResponseInterface;

    /**
     * Create an error formatted api response.
     *
     * @param mixed $content
     * @param null|mixed[] $headers
     *
     * @return \EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface
     */
    public function createError($content, ?array $headers = null): FormattedApiResponseInterface;

    /**
     * Create a forbidden formatted api response.
     *
     * @param null|mixed $content
     * @param null|mixed[] $headers
     *
     * @return \EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface
     */
    public function createForbidden($content = null, ?array $headers = null): FormattedApiResponseInterface;

    /**
     * Create a success formatted api response.
     *
     * @param mixed $content
     * @param null|mixed[] $headers
     *
     * @return \EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface
     */
    public function createSuccess($content, ?array $headers = null): FormattedApiResponseInterface;

    /**
     * Create an unauthorized formatted api response.
     *
     * @param null|mixed $content
     * @param null|mixed[] $headers
     *
     * @return \EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface
     */
    public function createUnauthorized($content = null, ?array $headers = null): FormattedApiResponseInterface;
}
