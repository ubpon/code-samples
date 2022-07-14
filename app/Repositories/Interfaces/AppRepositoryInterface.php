<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces;

use LoyaltyCorp\EasyRepository\Interfaces\PaginatedObjectRepositoryInterface;

interface AppRepositoryInterface extends PaginatedObjectRepositoryInterface
{
    /**
     * Find object for given identifier, throw a not found exception if not found.
     *
     * @param string $identifier
     *
     * @return object
     *
     * @throws \EoneoPay\Framework\Exceptions\EntityNotFoundException If entity not found for given identifier
     */
    public function findOrFail(string $identifier): object;

    /**
     * Flush entity manager.
     *
     * @return void
     */
    public function flush(): void;

    /**
     * Remove the given entities.
     *
     * @param mixed $entities
     *
     * @return void
     */
    public function remove($entities): void;
}
