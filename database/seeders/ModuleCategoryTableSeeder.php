<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ModuleCategory;

class ModuleCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', '=', 'superadmin')->first()->uuid;
        $categories = [
            'ACCESS',
            'AP',
            'SPBU',
            'NETWORK',
        ];
        if ($this->command->confirm('Seed data module category? [y|N]', true)) {
            $this->command->getOutput()->createProgressBar(count($categories));
            $this->command->getOutput()->progressStart();
            foreach ($categories as $category) {
                ModuleCategory::create([
                    'name' => $category,
                    'created_by' => $user,
                ]);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
            $this->command->info('Data module category inserted to database');
        }
    }
}
