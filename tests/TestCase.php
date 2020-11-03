<?php

namespace Braumye\LoginVerification\Tests;

use CreateLoginVerificationsTable;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Braumye\LoginVerification\LoginVerificationServiceProvider;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        include_once(__DIR__  . '/../database/migrations/create_login_verifications_tables.php.stub');
        (new CreateLoginVerificationsTable())->up();
    }

    protected function getPackageProviders($app)
    {
        return [
            LoginVerificationServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', 'base64:YHlz8/vvyEzLKAlX6DVgyDZ3RRjoOQCl6ESrx+TEk/Q=');
    }
}
