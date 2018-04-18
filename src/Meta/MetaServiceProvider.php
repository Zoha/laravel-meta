<?php


namespace Zoha\Meta;


use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Faker\Generator as Faker;
use Zoha\Meta\Helpers\MetaHelper;
use Zoha\Meta\Helpers\MetaInterface;

class MetaServiceProvider extends ServiceProvider
{

    //----------------------------------------- Properties ------------------------------------------//

    private $migrations = [
        __DIR__ . '/../../Database/Migrations',
    ];

    //------------------------------------------ Methods --------------------------------------------//

    /**
     * register method for service provider
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('meta' , function(){
            return new MetaInterface();
        });
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