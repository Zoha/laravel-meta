<?php

use Faker\Generator as Faker;
use Zoha\Meta\Helpers\MetaHelper as Meta;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


$factory->define(\Zoha\Meta\Models\ExampleModel::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(10),
    ];
});

$factory->define(\Zoha\Meta\Models\Meta::class , function(Faker $faker){
    return [
        'key' => $faker->unique()->word,
        'value' => $faker->sentence(3),
        'type' => Meta::META_TYPE_STRING,
        'status' => true
    ];
});

