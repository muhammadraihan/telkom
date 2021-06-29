<?php

namespace Database\Factories;

use App\Models\ModuleType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuleType::class;

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
            'name' => $this->faker->bothify('type ???####'),
            'created_by' => $user,
        ];
    }
}
