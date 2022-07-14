<?php
declare(strict_types=1);

namespace Tests\App\Tools\TestCases;

abstract class AbstractEntityTestCase extends AbstractDoctrineAnnotationsTestCase
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
     * Test entity association
     *
     * @param string $entityClass The entity class to test
     * @param string $associatedClass The associated entity class
     * @param string $property The association property
     *
     * @return void
     */
    protected function assertEntityAssociation(string $entityClass, string $associatedClass, string $property): void
    {
        $association = new $associatedClass();
        $entity = new $entityClass([$property => $association]);

        // Determine getter
        $getter = \sprintf('get%s', $property);
        self::assertInstanceOf($associatedClass, $entity->{$getter}());
        self::assertSame($association, $entity->{$getter}());
    }
}
