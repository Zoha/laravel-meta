<?php


namespace Zoha\Meta;


use Illuminate\Support\ServiceProvider;
use Zoha\Meta\Commands\CreateMetaModelCommand;

class MetaServiceProvider extends ServiceProvider
{

    //----------------------------------------- Properties ------------------------------------------//

    /*
     * package migrations
     */
    private $migrations = [
        __DIR__ . '/Database/Migrations',
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

        $this->registerPublishes();
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

    /**
     * register package migrations
     * 
     * @return void
     */
    private function registerPublishes()
    {
        $this->publishes([
            __DIR__ . '/Config/meta.php' => config_path('meta.php'),
        ], 'config');
    }
}