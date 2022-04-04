<?php

namespace Zoha\Meta\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Zoha\Meta\Helpers\MetaHelper;
use Zoha\Meta\Models\Meta;

class MetaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'key' => $this->faker->unique()->word,
            'value' => $this->faker->sentence(3),
            'type' => MetaHelper::META_TYPE_STRING,
            'status' => true
        ];
    }
}
