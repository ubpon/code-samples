<?php
declare(strict_types=1);

namespace Tests\App\Tools\TestCases;

use Doctrine\ORM\Mapping\Id;
use EoneoPay\Externals\ORM\Interfaces\EntityInterface;
use EoneoPay\Utils\AnnotationReader;
use Tests\App\AbstractTestCase;

/**
 * @coversNothing
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren) Suppress because many test uses this abstraction
 */
abstract class DoctrineAnnotationsTestCase extends AbstractTestCase
{
    /**
     * Assert the doGetRules returned array.
     *
     * @param string $entity
     * @param mixed[] $expected
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function assertDoGetRules(string $entity, array $expected): void
    {
        $doGetRules = $this->getMethodAsPublic($entity, 'doGetRules');

        self::assertEquals($expected, $doGetRules->invoke(new $entity()));
    }

    /**
     * Assert the doToArray return.
     *
     * @param string $entity
     * @param mixed[] $expected
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function assertDoToArray(string $entity, array $expected): void
    {
        $doToArray = $this->getMethodAsPublic($entity, 'doToArray');

        $this->assertArrayHasKeys($expected, $doToArray->invoke(new $entity()));
    }

    /**
     * Assert ID Property.
     *
     * @param string $entity
     * @param int|string $expected
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function assertIdProperty(string $entity, $expected): void
    {
        $method = $this->getMethodAsPublic($entity, 'getIdProperty');

        self::assertEquals($expected, $method->invoke(new $entity()));
    }

    /**
     * Require the annotations for doctrine and extensions.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Require Doctrine annotations
        require_once __DIR__ . '/../../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php';
        // Require Gedmo annotations
        require_once __DIR__ . '/../../../vendor/gedmo/doctrine-extensions/lib/Gedmo/Mapping/Annotation/All.php';
    }

    /**
     * Assert an identifier on a class matches the annotation
     *
     * @param string $entityClass The entity class to test
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\AnnotationCacheException If opcache isn't caching annotations
     * @throws \ReflectionException If entity isn't the right class
     */
    protected function assertIdentifier(string $entityClass): void
    {
        // Get id property
        $ids = (new AnnotationReader())->getClassPropertyAnnotation($entityClass, Id::class);
        $entityId = \key($ids);

        // Ensure there is an id
        self::assertNotNull($entityId);

        // Create entity
        $entity = new $entityClass();

        // Ensure entity is the right class
        self::assertInstanceOf(EntityInterface::class, $entity);
        /** @var \EoneoPay\Externals\ORM\Interfaces\EntityInterface $entity */
        $method = $this->getMethodAsPublic($entityClass, 'getIdProperty');
        self::assertSame($entityId, $method->invoke($entity));
    }
}
