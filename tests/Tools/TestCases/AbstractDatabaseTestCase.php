<?php
declare(strict_types=1);

namespace Tests\App\Tools\TestCases;

use App\Database\Entities\AbstractEntity;
use App\Database\Entities\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use EoneoPay\Externals\Bridge\Laravel\EventDispatcher;
use EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface;
use LoyaltyCorp\EasyRepository\Interfaces\ObjectRepositoryInterface;
use Tests\App\AbstractTestCase;

/**
 * @coversNothing
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) Test case sets up database for testing
 * @SuppressWarnings(PHPMD.NumberOfChildren) Test case, all database tests extend this
 */
abstract class AbstractDatabaseTestCase extends AbstractTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /** @var \App\Repositories\Doctrine\ORM\AbstractRepository */
    private $repository;

    /**
     * Assert response to be paginated.
     *
     * @param int $itemsCount
     *
     * @return void
     */
    public function assertPaginatedResponse(int $itemsCount): void
    {
        $response = $this->getResponseAsArray();

        $this->assertArrayHasKeys(['items', 'pagination'], $response);
        self::assertCount($itemsCount, $response['items']);
    }

    /**
     * Register an existing instance of the EventDispatcherInterface.
     *
     * @param  mixed[]|string $events
     *
     * @return \Tests\App\Tools\TestCases\AbstractDatabaseTestCase
     */
    public function expectsEvents($events): AbstractDatabaseTestCase
    {
        parent::expectsEvents($events);

        $this->app->instance(EventDispatcherInterface::class, new EventDispatcher($this->app->get('events')));

        return $this;
    }

    /**
     * Create database using doctrine command.
     *
     * @return void
     *
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function setUp(): void
    {
        parent::setUp();

        $schemaTool = new SchemaTool($this->getEntityManager());

        $schemaTool->createSchema($this->getEntityManager()->getMetadataFactory()->getAllMetadata());
    }

    /**
     * Reset database using doctrine command and close the connection.
     *
     * @return void
     */
    public function tearDown(): void
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->app->get('registry')->getManager();
        $entityManager->getConnection()->close();

        parent::tearDown();
    }

    /**
     * Should retrieve the catalogue.
     *
     * @param string $method
     * @param string $uri
     * @param null|mixed[] $body
     * @param null|mixed[] $header
     * @param \App\Database\Entities\User|null $user
     *
     * @return void
     */
    protected function adminJson(
        string $method,
        string $uri,
        ?array $body = null,
        ?array $header = null,
        ?User $user = null
    ): void {
        $user = $user ?? \entity(User::class)->create();

        $this->json(
            $method,
            $uri,
            $body ?? [],
            \array_merge(
                $header ?? [],
                [
                    'Authorization' => \sprintf('Basic %s', \base64_encode($user->getApiKey() . ':'))
                ]
            )
        );
    }

    /**
     * Get entity manager instance
     *
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        if ($this->entityManager !== null) {
            return $this->entityManager;
        }

        return $this->entityManager = $this->app->make('registry')->getManager();
    }

    /**
     * Get repository.
     *
     * @param string $repository
     *
     * @return \LoyaltyCorp\EasyRepository\Interfaces\ObjectRepositoryInterface
     */
    protected function getRepository(string $repository): ObjectRepositoryInterface
    {
        if ($this->repository instanceof $repository) {
            return $this->repository;
        }

        return $this->repository = \app($repository);
    }

    /**
     * Get the http response content as array.
     *
     * @return mixed[]
     */
    protected function getResponseAsArray(): array
    {
        return \json_decode($this->response->getContent(), true);
    }

    /**
     * Refresh the entity.
     *
     * @param \App\Database\Entities\AbstractEntity $entity
     *
     * @return void
     */
    protected function refreshEntity(AbstractEntity $entity): void
    {
        /** @var \Doctrine\Common\Persistence\ManagerRegistry $managerRegistry */
        $managerRegistry = $this->app->get('registry');

        $managerRegistry->getManager()->refresh($entity);
    }
}
