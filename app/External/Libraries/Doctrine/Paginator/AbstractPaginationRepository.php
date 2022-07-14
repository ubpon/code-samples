<?php
declare(strict_types=1);

namespace App\External\Libraries\Doctrine\Paginator;

use App\External\Interfaces\PaginatorInterface;
use Doctrine\ORM\AbstractQuery;
use EoneoPay\Externals\ORM\Repository;
use LaravelDoctrine\ORM\Pagination\PaginatorAdapter;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren) Suppress for dependency
 */
abstract class AbstractPaginationRepository extends Repository
{
    /**
     * Custom doctrine paginator.
     *
     * @var \App\External\Interfaces\PaginatorInterface
     */
    private $paginator;

    /**
     * Paginate repository query result.
     *
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return \App\External\Interfaces\PaginatorInterface
     *
     * @throws \EoneoPay\Externals\ORM\Exceptions\ORMException
     */
    public function paginate(?int $page = null, ?int $perPage = null): PaginatorInterface
    {
        return $this->makePaginator($this->createQueryBuilder('q')->getQuery(), $page, $perPage);
    }

    /**
     * Set custom doctrine paginator.
     *
     * @param \App\External\Interfaces\PaginatorInterface $paginator
     *
     * @return \App\External\Libraries\Doctrine\Paginator\AbstractPaginationRepository
     */
    public function setPaginator(PaginatorInterface $paginator): AbstractPaginationRepository
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * Make doctrine paginator.
     *
     * @param \Doctrine\ORM\AbstractQuery $query
     * @param int|null $page
     * @param int|null $perPage
     * @param bool|null $fetchJoinCollection
     *
     * @return \App\External\Interfaces\PaginatorInterface
     */
    protected function makePaginator(
        AbstractQuery $query,
        ?int $page = null,
        ?int $perPage = null,
        ?bool $fetchJoinCollection = null
    ): PaginatorInterface {
        if ($this->paginator !== null) {
            return $this->paginator;
        }

        return new PaginatorWrapper(
            PaginatorAdapter::fromParams(
                $query,
                $this->getPaginationValueWithDefault(15, $perPage),
                $this->getPaginationValueWithDefault(1, $page),
                $fetchJoinCollection ?? true
            )->make()
        );
    }

    /**
     * Get pagination value with defaults.
     *
     * @param int $default
     * @param int|null $value
     *
     * @return int
     */
    private function getPaginationValueWithDefault(int $default, ?int $value = null): int
    {
        if (empty($value)) {
            return $default;
        }

        return $value;
    }
}
