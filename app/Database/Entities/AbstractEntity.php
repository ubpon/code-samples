<?php
declare(strict_types=1);

namespace App\Database\Entities;

use App\Database\Exceptions\EntityValidationFailedException;
use DateTime;
use EoneoPay\Externals\ORM\Interfaces\ValidatableInterface;
use EoneoPay\Framework\Database\Entities\Entity as BaseEntity;
use EoneoPay\Utils\Interfaces\UtcDateTimeInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren) All entities extend this one
 */
abstract class AbstractEntity extends BaseEntity implements ValidatableInterface
{
    use TimestampableEntity;

    /**
     * Entity constructor.
     *
     * @param null|mixed[] $data
     *
     * @throws \Exception
     */
    public function __construct(?array $data = null)
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        parent::__construct($data);
    }

    /**
     * Get validation rules.
     *
     * @return mixed[]
     */
    public function getRules(): array
    {
        return \array_merge([
            'createdAt' => 'required|date',
            'updatedAt' => 'required|date'
        ], $this->doGetRules());
    }

    /**
     * Get all validatable properties for this entity
     *
     * @return string[]
     */
    public function getValidatableProperties(): array
    {
        return $this->getObjectProperties();
    }

    /**
     * Get validation failed exception class.
     *
     * @return string
     */
    public function getValidationFailedException(): string
    {
        return EntityValidationFailedException::class;
    }

    /**
     * Serialize entity as an array.
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        return \array_merge([
            'created_at' => $this->transformDateTimeToZuluFormat($this->getCreatedAt()),
            'updated_at' => $this->transformDateTimeToZuluFormat($this->getUpdatedAt())
        ], $this->doToArray());
    }

    /**
     * Get entity specific validation rules as an array.
     *
     * @return mixed[]
     */
    abstract protected function doGetRules(): array;

    /**
     * Serialize entity specific properties as an array.
     *
     * @return mixed[]
     */
    abstract protected function doToArray(): array;

    /**
     * Transform datetime object to Zulu formatted string
     *
     * @param null|\DateTime $dateTime
     *
     * @return null|string
     */
    protected function transformDateTimeToZuluFormat(?DateTime $dateTime = null): ?string
    {
        if ($dateTime === null) {
            return null;
        }

        return $dateTime->format(UtcDateTimeInterface::FORMAT_ZULU);
    }
}
