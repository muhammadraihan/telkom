<?php

namespace Database\Factories;

use App\Models\ModuleCategory;
use App\Models\ModuleName;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleNameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuleName::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // created by
        $user = User::where('name', '=', 'superadmin')->first()->uuid;
        // category
        $category = ModuleCategory::select('uuid')->get()->random();
        return [
            'name' => $this->faker->lexify('Name ????'),
            'module_category_uuid' => $category->uuid,
            'created_by' => $user,
        ];
    }
}
