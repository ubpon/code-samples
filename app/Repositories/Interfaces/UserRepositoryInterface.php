<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface extends AppRepositoryInterface
{
    /**
     * Find by first name.
     *
     * @param string $firstName
     *
     * @return mixed[]
     */
    public function findByFirstName(string $firstName): array;
}
