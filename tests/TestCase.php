<?php

namespace Zoha\Meta\Tests;

use Illuminate\Support\Facades\Schema;

class TestCase extends \Orchestra\Testbench\TestCase
{

    //------------------------------------------ Methods --------------------------------------------//

    /**
     * define package providers
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['Zoha\Meta\MetaServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            "Meta" => "Zoha\Meta\Facades\MetaFacade",
        ];
    }

    /**
     * define environment configs
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'laravelmeta');
        $app['config']->set('database.connections.laravelmeta', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * setup testing
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loadLaravelMigrations(['--database' => 'laravelmeta']);
        $this->loadMigrationsFrom(__DIR__ . '/../Database/TestCaseMigrations');
        $this->withFactories(__DIR__ . '/../Database/Factories');
        $this->artisan('migrate', ['--database' => 'laravelmeta']);
    }

    //--------------------------------------- Test Methods -----------------------------------------//

    public function test_connection_and_migrations()
    {
        $tableUsersExists = Schema::hasTable('model');
        $tableMetaExists = Schema::hasTable('meta');

        $this->assertTrue($tableUsersExists);
        $this->assertTrue($tableMetaExists);
    }
}