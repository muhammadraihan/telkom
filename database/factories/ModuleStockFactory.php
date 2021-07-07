<?php

namespace Database\Factories;

use App\Models\ModuleStock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleStockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuleStock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::where('name', '=', 'superadmin')->first()->uuid;
        return [
            'available' => $this->faker->randomDigitNotNull(),
            'created_by' => $user,
        ];
    }
}
