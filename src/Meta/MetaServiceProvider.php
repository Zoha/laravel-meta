<?php


namespace Zoha\Meta;


use Illuminate\Support\ServiceProvider;

class MetaServiceProvider extends ServiceProvider
{

    //----------------------------------------- Properties ------------------------------------------//

    private $migrations = [
        __DIR__ . '/../../database/Migrations',
    ];

    //------------------------------------------ Methods --------------------------------------------//

    /**
     * register method for service provider
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * boot method for service provider
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMigrations();
    }

    /**
     * register migrations for this package
     *
     * @return void
     */
    private function registerMigrations()
    {
        $this->loadMigrationsFrom($this->migrations);
    }
}