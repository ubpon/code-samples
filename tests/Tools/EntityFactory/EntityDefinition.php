<?php
declare(strict_types=1);

namespace Tests\App\Tools\EntityFactory;

use Faker\Generator as Faker;

final class EntityDefinition
{
    /**
     * @var null|string
     */
    private $class;

    /**
     * @var callable
     */
    private $closure;

    /**
     * EntityDefinition constructor.
     *
     * @param string $class
     * @param callable $closure
     */
    public function __construct(string $class, callable $closure)
    {
        $this->class = $class;
        $this->closure = $closure;
    }

    /**
     * Invoke class.
     *
     * @param \Faker\Generator $faker
     * @param null|mixed[] $attributes
     *
     * @return mixed
     */
    public function __invoke(Faker $faker, ?array $attributes = null)
    {
        $definition = \call_user_func($this->closure, $faker, $attributes);


        /** @var \EoneoPay\Externals\ORM\Interfaces\EntityInterface $definition */
        if ($definition instanceof \EoneoPay\Externals\ORM\Interfaces\EntityInterface) {
            $definition->fill($attributes ?? []);

            return $definition;
        }

        $class = $this->class;

        $result = \array_merge($definition, $attributes ?? []);

        foreach ($result as $key => $value) {
            if ($value instanceof \Closure) {
                $result[$key] = $value();
            }
        }

        return new $class($result);
    }
}
