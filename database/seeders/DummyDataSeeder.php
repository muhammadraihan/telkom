<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\ModuleBrand;
use App\Models\ModuleName;
use App\Models\ModuleStock;
use App\Models\ModuleType;
use App\Models\User;
use Illuminate\Database\Seeder;

use Hash;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // seed module dummy data
        $names = ModuleName::factory()->count(5)->create();
        if ($this->command->confirm('Seed module dummy data? [y|N]', true)) {
            $this->command->getOutput()->progressStart();
            $names->each(function ($name) {
                Material::factory()->count(5)->create([
                    'module_name_uuid' => $name->uuid,
                ]);
                ModuleBrand::factory()->count(2)->create([
                    'module_name_uuid' => $name->uuid,
                ])->each(function ($brand) {
                    ModuleType::factory()->count(2)->create([
                        'module_brand_uuid' => $brand->uuid,
                    ])->each(function ($type) {
                        ModuleStock::factory()->create([
                            'module_type_uuid' => $type->uuid,
                        ]);
                        $this->command->getOutput()->progressAdvance();
                    });
                });
            });
            $this->command->getOutput()->progressFinish();
            $this->command->info('Module dummy data inserted to database');
        }

        // seed technician dummy data
        $tech = [
            ['name' => 'tech1', 'email' => 'tech1@app.com', 'password' => Hash::make('password')],
            ['name' => 'tech2', 'email' => 'tech2@app.com', 'password' => Hash::make('password')],
            ['name' => 'tech3', 'email' => 'tech3@app.com', 'password' => Hash::make('password')],
            ['name' => 'tech4', 'email' => 'tech4@app.com', 'password' => Hash::make('password')],
            ['name' => 'tech5', 'email' => 'tech5@app.com', 'password' => Hash::make('password')],
        ];
        if ($this->command->confirm('Seed repair dummy data? [y|N]', true)) {
            $this->command->getOutput()->createProgressBar(count($tech));
            $this->command->getOutput()->progressStart();
            foreach ($tech as $repair) {
                $user = User::create($repair);
                $user->assignRole('repair');
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
            $this->command->info('Repair dummy data inserted to database');
        }

        // seed supervisi dummy data
        $warehouse = [
            ['name' => 'supervisi', 'email' => 'supervisi@app.com', 'password' => Hash::make('password')],
        ];
        if ($this->command->confirm('Seed supervisi dummy data? [y|N]', true)) {
            $this->command->getOutput()->createProgressBar(count($warehouse));
            $this->command->getOutput()->progressStart();
            foreach ($warehouse as $super) {
                $user = User::create($super);
                $user->assignRole('supervisi');
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
            $this->command->info('Supervisi dummy data inserted to database');
        }

        // seed warehouse dummy data
        $warehouse = [
            ['name' => 'warehouse', 'email' => 'warehouse@app.com', 'password' => Hash::make('password')],
        ];
        if ($this->command->confirm('Seed warehouse dummy data? [y|N]', true)) {
            $this->command->getOutput()->createProgressBar(count($warehouse));
            $this->command->getOutput()->progressStart();
            foreach ($warehouse as $ware) {
                $user = User::create($ware);
                $user->assignRole('warehouse');
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
            $this->command->info('Warehouse dummy data inserted to database');
        }

        // seed warehouse dummy data
        $customer = [
            ['name' => 'customer care', 'email' => 'customer-care@app.com', 'password' => Hash::make('password')],
        ];
        if ($this->command->confirm('Seed customer care dummy data? [y|N]', true)) {
            $this->command->getOutput()->createProgressBar(count($customer));
            $this->command->getOutput()->progressStart();
            foreach ($customer as $custom) {
                $user = User::create($custom);
                $user->assignRole('customer-care');
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
            $this->command->info('Customer care dummy data inserted to database');
        }
    }
}
