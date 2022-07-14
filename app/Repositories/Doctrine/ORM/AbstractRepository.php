<?php
declare(strict_types=1);

namespace App\Repositories\Doctrine\ORM;

use App\Repositories\Interfaces\AppRepositoryInterface;
use EoneoPay\Framework\Exceptions\EntityNotFoundException;
use LoyaltyCorp\EasyRepository\Implementations\Doctrine\ORM\AbstractPaginatedDoctrineOrmRepository;

abstract class AbstractRepository extends AbstractPaginatedDoctrineOrmRepository implements AppRepositoryInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $manager;

    /**
     * Find object for given identifier, throw a not found exception if not found.
     *
     * @param string $identifier
     *
     * @return object
     *
     * @throws \EoneoPay\Framework\Exceptions\EntityNotFoundException If entity not found for given identifier
     */
    public function findOrFail(string $identifier): object
    {
        $entity = $this->find($identifier);

        if ($entity !== null) {
            return $entity;
        }

        throw $this->entityNotFoundException(['id' => $identifier]);
    }

    /**
     * Flush entity manager.
     *
     * @return void
     */
    public function flush(): void
    {
        $this->manager->flush();
    }

    /**
     * Remove the given entities.
     *
     * @param mixed $entities
     *
     * @return void
     */
    public function remove($entities): void
    {
        if (\is_array($entities) === false) {
            $entities = [$entities];
        }

        foreach ($entities as $entity) {
            $this->manager->remove($entity);
        }
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * Persis given object(s).
     *
     * @param object|object[] $object The object or list of objects to save
     *
     * @return void
     */
    public function save($object): void
    {
        if (\is_array($object) === false) {
            $object = [$object];
        }

        foreach ($object as $entity) {
            $this->manager->persist($entity);
        }
    }

    /**
     * Create entity not found exception.
     *
     * @param mixed[] $attributes
     * @param null|string $entityClass
     *
     * @return \EoneoPay\Framework\Exceptions\EntityNotFoundException
     */
    protected function entityNotFoundException(array $attributes, ?string $entityClass = null): EntityNotFoundException
    {
        $attributesAsString = [];

        foreach ($attributes as $name => $value) {
            $attributesAsString[] = \sprintf('%s: %s', $name, $value);
        }

        return new EntityNotFoundException('exceptions.entity.not_found', [
            'entity' => $entityClass ?? $this->getEntityClass(),
            'id' => \implode(', ', $attributesAsString)
        ]);
    }

    /**
     * Find one by criteria or fail.
     *
     * @param mixed[] $criteria
     *
     * @return object
     *
     * @throws \EoneoPay\Framework\Exceptions\EntityNotFoundException
     */
    protected function findOneByOrFail(array $criteria): object
    {
        $entity = $this->repository->findOneBy($criteria);

        if ($entity !== null) {
            return $entity;
        }

        throw $this->entityNotFoundException($criteria);
    }
}
