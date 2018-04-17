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
        __DIR__ . '/../../Database/TestCaseMigrations',
    ];

    private $factories = __DIR__ . '/../../Database/Factories';

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
        if(\Meta::isOnDevelop()){
            $this->registerFactories();
        }
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
     * register factories for this package
     *
     * @return void
     */
    private function registerFactories()
    {
        $this->app->singleton(EloquentFactory::class, function ($app) {
            $faker = $app->make(Faker::class);
            return EloquentFactory::construct($faker, $this->factories);
        });
    }
}