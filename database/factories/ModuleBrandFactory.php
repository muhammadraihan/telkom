<?php

namespace Database\Factories;

use App\Models\ModuleBrand;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleBrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuleBrand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // created by
        $user = User::where('name', '=', 'superadmin')->first()->uuid;
        return [
            'name' => $this->faker->lexify('brand ?????'),
            'created_by' => $user,
        ];
    }
}
