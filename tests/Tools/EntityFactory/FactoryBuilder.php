<?php
declare(strict_types=1);

namespace Tests\App\Tools\EntityFactory;

use Illuminate\Support\Collection;
use LaravelDoctrine\ORM\Testing\FactoryBuilder as DoctrineFactoryBuilder;

final class FactoryBuilder extends DoctrineFactoryBuilder
{
    /**
     * Uses the factory builder make and persist the objects.
     *
     * @param mixed[] $attributes
     *
     * @return mixed
     */
    public function persist(array $attributes = [])
    {
        $objects = parent::make($attributes);

        if (($objects instanceof Collection) === true) {
            foreach ($objects as $object) {
                $this->registry->getManager()->persist($object);
            }

            return $objects;
        }

        $this->registry->getManager()->persist($objects);

        return $objects;
    }
}
