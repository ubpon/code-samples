<?php
declare(strict_types=1);

namespace Tests\App;

use App\Factories\ApiResponse\ApiResponseFactoryInterface;
use Closure;
use EoneoPay\Externals\Container\Interfaces\ContainerInterface;
use EoneoPay\Externals\HttpClient\Response;
use EoneoPay\Externals\Translator\Interfaces\TranslatorInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use GuzzleHttp\Psr7\Response as PsrResponse;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use Laravel\Lumen\Application as LumenApplication;
use Laravel\Lumen\Testing\TestCase as LumenTestCase;
use LoyaltyCorp\EasyRepository\Interfaces\ObjectRepositoryInterface;
use Mockery;
use Mockery\MockInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) Suppress due to dependency for testing.
 * @SuppressWarnings(PHPMD.DeptOfInheritance) Suppress for testing.
 * @SuppressWarnings(PHPMD.NumberOfChildren) Suppress due to dependency.
 * @SuppressWarnings(PHPMD.StaticAccess) Suppress for testing.
 * @SuppressWarnings(PHPMD.StaticAccess) Suppress for testing.
 */
abstract class AbstractTestCase extends LumenTestCase
{
    /**
     * @var \App\Factories\ApiResponse\ApiResponseFactoryInterface
     */
    private $apiResponseFactory;

    /**
     * @var \Illuminate\Contracts\Console\Kernel
     */
    private $console;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var \LoyaltyCorp\EasyRepository\Interfaces\ObjectRepositoryInterface|\App\Repositories\Interfaces\AppRepositoryInterface
     */
    private $repository;

    /**
     * @var \EoneoPay\Externals\Translator\Interfaces\TranslatorInterface
     */
    private $translator;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication(): LumenApplication
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    /**
     * Create your SycnFeedCommand for testing.
     *
     * @param string $className
     * @param \Closure|null $input
     * @param \Closure|null $output
     *
     * @return \Illuminate\Console\Command
     *
     * @throws \ReflectionException
     */
    public function createCommand(string $className, ?Closure $input = null, ?Closure $output = null): Command
    {
        /** @var \Symfony\Component\Console\Input\InputInterface $inputInterface */
        $inputInterface = $this->mock(InputInterface::class, $input);

        /** @var \Symfony\Component\Console\Output\OutputInterface $outputInterface */
        $outputInterface = $this->mock(OutputInterface::class, $output);

        $command = $this->app->make($className);
        $command->setLaravel($this->app);

        $this->getPropertyAsPublic($className, 'input')->setValue($command, $inputInterface);
        $this->getPropertyAsPublic($className, 'output')->setValue($command, $outputInterface);

        return $command;
    }

    /**
     * Should retrieve the entity repository.
     *
     * @param string $repositoryInterface
     *
     * @return \StepTheFkUp\EasyRepository\Interfaces\ObjectRepositoryInterface
     */
    public function getEntityRepository(string $repositoryInterface): ObjectRepositoryInterface
    {
        return $this->app->make($repositoryInterface);
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->app->instance(
            Request::class,
            Request::create('http://localhost')
        );
    }

    /**
     * Assert array to be equal to expected data with only given expected keys from expected data.
     *
     * @param mixed[] $expected
     * @param mixed[] $assertData
     * @param null|string[] $excludedKeys
     *
     * @return void
     */
    protected function assertArrayEquals(array $expected, array $assertData, ?array $excludedKeys = null): void
    {
        /** @var string[] $excludedKeys */
        $excludedKeys = ['created_at', 'updated_at'] + ($excludedKeys ?? []);

        /**
         * @var string $key
         * @var mixed $value
         */
        foreach ($expected as $key => $value) {
            if (\in_array($key, $excludedKeys) === true) {
                continue;
            }

            self::assertArrayHasKey($key, $assertData);
            self::assertEquals($expected[$key], $assertData[$key]);
        }
    }

    /**
     * Assert given array has all given keys.
     *
     * @param string[] $keys
     * @param mixed[] $array
     *
     * @return void
     */
    protected function assertArrayHasKeys(array $keys, array $array): void
    {
        // Sort arrays
        \sort($keys);
        \ksort($array);

        self::assertSame($keys, \array_keys($array));
    }

    /**
     * Assert the instance of a service from external container.
     *
     * @param string $abstract
     * @param string $concrete
     *
     * @return void
     */
    protected function assertServiceInstanceOf(string $abstract, string $concrete): void
    {
        self::assertInstanceOf($concrete, $this->app->make(ContainerInterface::class)->get($abstract));
    }

    /**
     * Get closure from private service provider method.
     *
     * @param string $expected
     * @param string $providerClass
     * @param string $method
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    protected function assertServiceProviderClosureInstance(
        string $expected,
        string $providerClass,
        string $method
    ): void {
        $getClosure = $this->getMethodAsPublic($providerClass, $method);
        /** @noinspection PhpParamsInspection Inherited from Laravel */
        $closure = $getClosure->invoke(new $providerClass($this->app));

        self::assertInstanceOf($expected, $closure());
    }

    /**
     * Get ApiResponseFactory instance.
     *
     * @return \App\Factories\ApiResponse\ApiResponseFactoryInterface
     */
    protected function getApiResponseFactory(): ApiResponseFactoryInterface
    {
        if ($this->apiResponseFactory !== null) {
            return $this->apiResponseFactory;
        }

        return $this->apiResponseFactory = $this->app->get(ApiResponseFactoryInterface::class);
    }

    /**
     * Get console kernel instance.
     *
     * @return \Illuminate\Contracts\Console\Kernel
     */
    protected function getConsole(): Kernel
    {
        if ($this->console !== null) {
            return $this->console;
        }

        return $this->console = $this->app->make(Kernel::class);
    }

    /**
     * Get faker instance.
     *
     * @return \Faker\Generator
     */
    protected function getFaker(): Generator
    {
        if ($this->faker !== null) {
            return $this->faker;
        }

        return $this->faker = FakerFactory::create();
    }

    /**
     * Convert protected/private method to public.
     *
     * @param string $className
     * @param string $methodName
     *
     * @return \ReflectionMethod
     *
     * @throws \ReflectionException
     */
    protected function getMethodAsPublic(string $className, string $methodName): ReflectionMethod
    {
        $class = new ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * Convert protected/private property to public.
     *
     * @param string $className
     * @param string $propertyName
     *
     * @return \ReflectionProperty
     *
     * @throws \ReflectionException
     */
    protected function getPropertyAsPublic(string $className, string $propertyName): ReflectionProperty
    {
        $class = new ReflectionClass($className);
        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * Get repository.
     *
     * @param string $repository
     *
     * @return \LoyaltyCorp\EasyRepository\Interfaces\ObjectRepositoryInterface|\App\Repositories\Interfaces\AppRepositoryInterface
     */
    protected function getRepository(string $repository): ObjectRepositoryInterface
    {
        if ($this->repository instanceof $repository) {
            return $this->repository;
        }

        return $this->repository = \app($repository);
    }

    /**
     * Get TranslatorInterface instance.
     *
     * @return \EoneoPay\Externals\Translator\Interfaces\TranslatorInterface
     */
    protected function getTranslator(): TranslatorInterface
    {
        if ($this->translator !== null) {
            return $this->translator;
        }

        return $this->translator = $this->app->get(TranslatorInterface::class);
    }

    /**
     * Create mock for given class and set expectations using given closure.
     *
     * @param string $class
     * @param \Closure|null $setExpectations
     *
     * @return \Mockery\MockInterface
     *
     * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery)
     */
    protected function mock(string $class, ?Closure $setExpectations = null): MockInterface
    {
        $mock = Mockery::mock($class);

        // If no expectations, early return
        if ($setExpectations === null) {
            return $mock;
        }

        // Pass mock to closure to set expectations
        $setExpectations($mock);

        return $mock;
    }

    /**
     * Create a response object.
     *
     * @param int $status
     * @param string $contentType
     * @param mixed[] $body
     *
     * @return \EoneoPay\Externals\HttpClient\Response
     */
    protected function response(?int $status = null, ?string $contentType = null, ?array $body = null): Response
    {
        $data = '';

        if ($body !== null) {
            $data = \json_encode($body);
        }

        return new Response(
            new PsrResponse($status ?? 200, ['Content-Type' => $contentType ?? 'application/json'], $data ?: '')
        );
    }
}
