<?php

namespace Database\Factories;

use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Material::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'material_type' => $this->faker->bothify('Material ##??'),
            'material_description' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'volume' => $this->faker->randomElement($array = array('buah', 'kotak')),
            'available' => $this->faker->randomDigitNotNull(),
            'unit_price' => $this->faker->numberBetween($min = 100000, $max = 1000000),
        ];
    }
}
