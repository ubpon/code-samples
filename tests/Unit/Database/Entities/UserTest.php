<?php
declare(strict_types=1);

namespace Tests\App\Unit\Database\Entities;

use App\Database\Entities\User;
use Tests\App\Tools\TestCases\DoctrineAnnotationsTestCase;

/**
 * @covers \App\Database\Entities\User
 */
final class UserTest extends DoctrineAnnotationsTestCase
{
    /**
     * Test entity do get rules.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function testDoGetRules(): void
    {
        $this->assertDoGetRules(User::class, [
            'active' => 'boolean',
            'email' => 'required|email',
            'firstName' => 'required|string',
            'lastName' => 'required|string'
        ]);
    }

    /**
     * Test do to array.
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function testDoToArray(): void
    {
        $this->assertDoToArray(User::class, [
            'active',
            'email',
            'id',
            'first_name',
            'last_name'
        ]);
    }

    /**
     * Test assert id property.
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function testAssertIdProperty(): void
    {
        $this->assertIdProperty(User::class, 'userId');
    }
}
