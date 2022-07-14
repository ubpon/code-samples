<?php
declare(strict_types=1);

namespace App\Factories\ApiResponse;

use EoneoPay\ApiFormats\Bridge\Laravel\Responses\FormattedApiResponse;
use EoneoPay\ApiFormats\Bridge\Laravel\Responses\NoContentApiResponse;
use EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface;
use EoneoPay\Externals\Translator\Interfaces\TranslatorInterface;

final class ApiResponseFactory implements ApiResponseFactoryInterface
{
    /**
     * @var \EoneoPay\Externals\Translator\Interfaces\TranslatorInterface
     */
    private $translator;

    /**
     * ApiResponseFactory constructor.
     *
     * @param \EoneoPay\Externals\Translator\Interfaces\TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Create a formatted api response for given parameters.
     *
     * @param mixed $content
     * @param null|int $statusCode
     * @param null|mixed[] $headers
     *
     * @return \EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface
     */
    public function create($content, ?int $statusCode = null, ?array $headers = null): FormattedApiResponseInterface
    {
        return new FormattedApiResponse($content, $statusCode, $headers);
    }

    /**
     * Create an empty formatted api response.
     *
     * @param null|mixed[] $headers
     *
     * @return \EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface
     */
    public function createEmpty(?array $headers = null): FormattedApiResponseInterface
    {
        return new NoContentApiResponse(null, $headers);
    }

    /**
     * Create an error formatted api response.
     *
     * @param mixed $content
     * @param null|mixed[] $headers
     *
     * @return \EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface
     */
    public function createError($content, ?array $headers = null): FormattedApiResponseInterface
    {
        return $this->create($content, 500, $headers);
    }

    /**
     * Create a forbidden formatted api response.
     *
     * @param null|mixed $content
     * @param null|mixed[] $headers
     *
     * @return \EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface
     */
    public function createForbidden($content = null, ?array $headers = null): FormattedApiResponseInterface
    {
        $content = $content ?? ['message' => $this->translator->trans('responses.forbidden')];

        return $this->create($content, 403, $headers);
    }

    /**
     * Create a success formatted api response.
     *
     * @param mixed $content
     * @param null|mixed[] $headers
     *
     * @return \EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface
     */
    public function createSuccess($content, ?array $headers = null): FormattedApiResponseInterface
    {
        return $this->create($content, 201, $headers);
    }

    /**
     * Create an unauthorized formatted api response.
     *
     * @param null|mixed $content
     * @param null|mixed[] $headers
     *
     * @return \EoneoPay\ApiFormats\Interfaces\FormattedApiResponseInterface
     */
    public function createUnauthorized($content = null, ?array $headers = null): FormattedApiResponseInterface
    {
        $content = $content ?? ['message' => $this->translator->trans('responses.unauthorized')];

        return $this->create($content, 401, $headers);
    }
}
