<?php
declare(strict_types=1);

namespace App\Database\Entities;

use App\Database\Schema\UserSchema;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class User extends AbstractEntity
{
    use UserSchema;

    /**
     * Get array representation of children.
     *
     * @return mixed[]
     */
    protected function doToArray(): array
    {
        return [
            'active' => $this->isActive(),
            'email' => $this->getEmail(),
            'id' => $this->getUserId(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName()
        ];
    }

    /**
     * Get entity specific validation rules as an array.
     *
     * @return mixed[]
     */
    protected function doGetRules(): array
    {
        return [
            'active' => 'boolean',
            'email' => 'required|email',
            'firstName' => 'required|string',
            'lastName' => 'required|string'
        ];
    }

    /**
     * Get the id property for this entity
     *
     * @return string
     */
    protected function getIdProperty(): string
    {
        return 'userId';
    }
}
