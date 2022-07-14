<?php
declare(strict_types=1);

namespace App\External\Libraries\Doctrine\Paginator;

use App\External\Interfaces\PaginationDataInterface;
use EoneoPay\Externals\Request\Interfaces\RequestInterface;

class PaginationData implements PaginationDataInterface
{
    /**
     * Default page value.
     *
     * @var int
     */
    private const PAGE_DEFAULT = 1;

    /**
     * Page query name.
     *
     * @var string
     */
    private const PAGE_QUERY = 'page';

    /**
     * Default per page value.
     *
     * @var int
     */
    private const PER_PAGE_DEFAULT = 15;

    /**
     * Number of item per page query name.
     *
     * @var string
     */
    private const PER_PAGE_QUERY = 'perPage';

    /**
     * Request interface.
     *
     * @var \EoneoPay\Externals\Request\Interfaces\RequestInterface
     */
    private $request;

    /**
     * PaginationData constructor.
     *
     * @param \EoneoPay\Externals\Request\Interfaces\RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Get current page from request.
     *
     * @return int
     */
    public function getPage(): int
    {
        return (int)($this->request->input(self::PAGE_QUERY) ?? self::PAGE_DEFAULT);
    }

    /**
     * Get number of items per page.
     *
     * @return int
     */
    public function getPerPage(): int
    {
        return (int)($this->request->input(self::PER_PAGE_QUERY) ?? self::PER_PAGE_DEFAULT);
    }
}
