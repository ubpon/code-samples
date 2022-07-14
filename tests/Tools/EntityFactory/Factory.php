<?php
declare(strict_types=1);

namespace Tests\App\Tools\EntityFactory;

use LaravelDoctrine\ORM\Testing\Factory as LaravelDoctrineFactory;
use LaravelDoctrine\ORM\Testing\FactoryBuilder as DortrineFactoryBuilder;

final class Factory extends LaravelDoctrineFactory
{
    /**
     * Override to use EntityDefinition.
     *
     * @param mixed $class
     * @param callable $attributes
     * @param mixed $name
     *
     * @return void
     */
    public function define($class, callable $attributes, $name = 'default'): void
    {
        parent::define($class, new EntityDefinition($class, $attributes), $name);
    }

    /**
     * Override the doctrine factory of to use the custom factory builder.
     *
     * @param mixed $class
     * @param mixed $name
     *
     * @return \LaravelDoctrine\ORM\Testing\FactoryBuilder
     */
    public function of($class, $name = 'default'): DortrineFactoryBuilder
    {
        return FactoryBuilder::construct(
            $this->registry,
            $class,
            $name,
            $this->definitions,
            $this->faker,
            $this->getStateFor($class)
        );
    }
}
