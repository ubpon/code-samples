<?php
declare(strict_types=1);

namespace App\Repositories\Doctrine\ORM;

use App\Database\Entities\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

final class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    /**
     * Find by first name.
     *
     * @param string $firstName
     *
     * @return mixed[]
     */
    public function findByFirstName(string $firstName): array
    {
        return $this->repository->findBy(['firstName' => $firstName]);
    }

    /**
     * Get entity class managed by the repository.
     *
     * @return string
     */
    protected function getEntityClass(): string
    {
        return User::class;
    }
}
