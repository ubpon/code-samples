<?php
declare(strict_types=1);

namespace Tests\App\Tools\TestCases;

use Tests\App\AbstractTestCase;

/**
 * @coversNothing
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren) Suppress due to dependency.
 */
abstract class AbstractDoctrineAnnotationsTestCase extends AbstractTestCase
{
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
}
