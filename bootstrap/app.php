<?php
declare(strict_types=1);

$basePath = \realpath(\dirname(__DIR__));
require_once $basePath . '/vendor/autoload.php';

try {
    (new \EoneoPay\Externals\Environment\Loader($basePath))->load();
} catch (\Dotenv\Exception\InvalidPathException $exception) {
    (new \EoneoPay\Externals\Logger\Logger())->exception($exception);
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new \Laravel\Lumen\Application(\dirname(__DIR__));

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    \EoneoPay\ApiFormats\Bridge\Laravel\Middlewares\ApiFormatsMiddleware::class,
]);

$app->routeMiddleware([]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/
$app->register(\EoneoPay\ApiFormats\Bridge\Laravel\Providers\ApiFormatsServiceProvider::class);
$app->register(\EoneoPay\Utils\Bridge\Lumen\Providers\ConfigurationServiceProvider::class);
$app->register(\EoneoPay\Externals\Bridge\Laravel\Providers\EnvServiceProvider::class);
$app->register(\EoneoPay\Externals\Bridge\Laravel\Providers\RequestServiceProvider::class);
$app->register(\EoneoPay\Externals\Bridge\Laravel\Providers\TranslatorServiceProvider::class);
$app->register(\EoneoPay\Externals\Bridge\Laravel\Providers\ValidationServiceProvider::class);
$app->register(\LaravelDoctrine\ORM\DoctrineServiceProvider::class);
$app->register(\EoneoPay\Externals\Bridge\Laravel\Providers\EventDispatcherServiceProvider::class);
$app->register(\LaravelDoctrine\Extensions\GedmoExtensionsServiceProvider::class);
$app->register(\LaravelDoctrine\Migrations\MigrationsServiceProvider::class);
$app->register(\StepTheFkUp\EasyPsr7Factory\Bridge\Laravel\EasyPsr7FactoryServiceProvider::class);
$app->register(\StepTheFkUp\EasyPagination\Bridge\Laravel\Providers\StartSizeInQueryEasyPaginationProvider::class);
$app->register(\StepTheFkUp\EasyRepository\Bridge\Laravel\EasyRepositoryProvider::class);
$app->register(\EoneoPay\Externals\Bridge\Laravel\Providers\ContainerServiceProvider::class);
$app->register(\EoneoPay\Externals\Bridge\Laravel\Providers\FilesystemServiceProvider::class);
$app->register(\EoneoPay\Externals\Bridge\Laravel\Providers\HttpClientServiceProvider::class);
$app->register(\EoneoPay\Externals\Bridge\Laravel\Providers\OrmServiceProvider::class);

// Application Service Providers
$app->register(\App\Providers\AppServiceProvider::class);
$app->register(\App\Providers\AuthServiceProvider::class);
$app->register(\App\Providers\FactoriesServiceProvider::class);
$app->register(\App\Providers\TransformersServiceProvider::class);
$app->register(\App\Providers\EventServiceProvider::class);
$app->register(\StepTheFkUp\EasyIdentity\Bridge\Laravel\Auth0IdentityServiceProvider::class);

// Application config
$app->configure('easy-identity');
/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/
$app->bind(\Illuminate\Validation\PresenceVerifierInterface::class,
    \LaravelDoctrine\ORM\Validation\DoctrinePresenceVerifier::class);
$app->bind(\EoneoPay\Externals\Logger\Interfaces\LoggerInterface::class, \EoneoPay\Externals\Logger\Logger::class);

$app->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, \App\Exceptions\Handler::class);
$app->singleton(\Illuminate\Contracts\Console\Kernel::class, \App\Console\Kernel::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

// Use directly request because container is not aware of it yet
$versionHelper = new \EoneoPay\Framework\Helpers\VersionHelper(
    $basePath,
    $app->make(\EoneoPay\Externals\Request\Interfaces\RequestInterface::class)
);

/** @noinspection PhpUnhandledExceptionInspection Exception thrown if version is invalid */
$app->router->group(
    ['namespace' => $versionHelper->getControllersNamespace()],
    function (\Laravel\Lumen\Routing\Router $router) use ($versionHelper) {
        // Include version routes
        /** @noinspection PhpIncludeInspection Dynamic including required for version purposes */
        require $versionHelper->getRoutesFileBasePath();

        // Add ping/pong for ELB, this can't be covered because it's not in a class
        $router->get('ping', 'HealthCheckController@ping');
    }
);

return $app;
