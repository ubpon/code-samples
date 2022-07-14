<?php
declare(strict_types=1);

namespace App\External\Interfaces;

interface PaginationDataInterface
{
    /**
     * Get current page from request.
     *
     * @return int
     */
    public function getPage(): int;

    /**
     * Get number of items per page.
     *
     * @return int
     */
    public function getPerPage(): int;
}
