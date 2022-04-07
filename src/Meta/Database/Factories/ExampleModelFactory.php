<?php

namespace Zoha\Meta\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Zoha\Meta\Models\ExampleModel;

class ExampleModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExampleModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(10),
        ];
    }
}
