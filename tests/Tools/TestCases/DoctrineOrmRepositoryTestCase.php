<?php
declare(strict_types=1);

namespace Tests\App\Tools\TestCases;

use Closure;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Mockery\MockInterface;

abstract class DoctrineOrmRepositoryTestCase extends AbstractDatabaseTestCase
{
    /**
     * Mock Doctrine manager registry for given manager and repository expectations.
     *
     * @param string $entityClass
     * @param null|\Closure $managerExpectations
     * @param null|\Closure $repoExpectations
     *
     * @return \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected function mockRegistry(
        string $entityClass,
        ?Closure $managerExpectations = null,
        ?Closure $repoExpectations = null
    ): ManagerRegistry {
        /** @var \Doctrine\Common\Persistence\ManagerRegistry $registry */
        $registry = $this->mock(
            ManagerRegistry::class,
            function (MockInterface $registry) use ($entityClass, $managerExpectations, $repoExpectations): void {
                $manager = $this->mock(ObjectManager::class, $managerExpectations);
                $repository = $this->mock(ObjectRepository::class, $repoExpectations);

                $manager->shouldReceive('getRepository')->once()->with($entityClass)->andReturn($repository);
                $registry->shouldReceive('getManagerForClass')->once()->with($entityClass)->andReturn($manager);
            }
        );

        return $registry;
    }
}
