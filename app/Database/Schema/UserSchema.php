<?php
declare(strict_types=1);

namespace App\Database\Schema;

use Doctrine\ORM\Mapping as ORM;

/**
 * @method bool isActive()
 * @method null|string getEmail()
 * @method null|string getFirstName()
 * @method null|string getLastName()
 * @method null|string getUserId()
 * @method self setActive(bool $active)
 * @method self setEmail(string $email)
 * @method self setFirstName(string $firstName)
 * @method self setLastName(string $lastName)
 */
trait UserSchema
{
    /**
     * @ORM\Column(type="boolean", name="active")
     *
     * @var bool
     */
    protected $active = true;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(type="string", name="first_name")
     *
     * @var string
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", name="last_name")
     *
     * @var string
     */
    protected $lastName;

    /**
     * @ORM\Column(type="guid", name="id")
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Id()
     *
     * @var string
     */
    protected $userId;
}
