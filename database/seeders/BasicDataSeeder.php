<?php

namespace Database\Seeders;

use App\Models\Accessory;
use App\Models\ModuleCategory;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Models\Witel;
use Illuminate\Database\Seeder;

class BasicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', '=', 'superadmin')->first()->uuid;
        // seeds data witel
        $witels = [
            ['name' => 'ACEH', 'created_by' => $user],
            ['name' => 'MEDAN', 'created_by' => $user],
            ['name' => 'SUMUT', 'created_by' => $user],
            ['name' => 'SUMBAR', 'created_by' => $user],
            ['name' => 'RIDAR', 'created_by' => $user],
            ['name' => 'RIKEP', 'created_by' => $user],
            ['name' => 'JAMBI', 'created_by' => $user],
            ['name' => 'BENGKULU', 'created_by' => $user],
            ['name' => 'PALEMBANG', 'created_by' => $user],
            ['name' => 'LAMPUNG', 'created_by' => $user],
            ['name' => 'BABEL', 'created_by' => $user],
            ['name' => 'SDA', 'created_by' => $user],
        ];
        if ($this->command->confirm('Seed data witel? [y|N]', true)) {
            $this->command->getOutput()->createProgressBar(count($witels));
            $this->command->getOutput()->progressStart();
            foreach ($witels as $witel) {
                Witel::create($witel);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
            $this->command->info('Data witel inserted to database');
        }

        // seed data unit
        $witels = Witel::select('uuid', 'name')->get();
        $units = [
            'NETWORK',
            'CCAN',
            'AOM',
            'GENERAL'
        ];

        if ($this->command->confirm('Seed data witel? [y|N]', true)) {
            foreach ($witels as $key => $value) {
                $this->command->info('Seeding unit for witel ' . $value->name);
                $this->command->getOutput()->createProgressBar(count($units));
                $this->command->getOutput()->progressStart();
                foreach ($units as $unit) {
                    Unit::create([
                        'name' => $unit,
                        'witel_uuid' => $value->uuid,
                        'created_by' => $user,
                    ]);
                    $this->command->getOutput()->progressAdvance();
                }
                $this->command->getOutput()->progressFinish();
                $this->command->info('Status: OK');
            }
            $this->command->info('Data unit inserted to database');
        }

        // seed data accessories
        $kelengkapans = [
            ['name' => 'Box', 'created_by' => $user],
            ['name' => 'Power Chord', 'created_by' => $user],
            ['name' => 'RJ45 Cable', 'created_by' => $user],
            ['name' => 'RJ11 Cable', 'created_by' => $user],
            ['name' => 'HDMI Cable', 'created_by' => $user],
            ['name' => 'Remote', 'created_by' => $user],
            ['name' => 'User Guide', 'created_by' => $user],
            ['name' => 'CD Driver', 'created_by' => $user],
        ];
        if ($this->command->confirm('Seed data kelengkapan? [y|N]', true)) {
            $this->command->getOutput()->createProgressBar(count($kelengkapans));
            $this->command->getOutput()->progressStart();
            foreach ($kelengkapans as $kelengkapan) {
                Accessory::create($kelengkapan);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
            $this->command->info('Data kelengkapan inserted to database');
        }

        // seed data module categories
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

        // seed data roles
        $roles = [
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'supervisi', 'guard_name' => 'web'],
            ['name' => 'repair', 'guard_name' => 'web'],
            ['name' => 'warehouse', 'guard_name' => 'web'],
            ['name' => 'customer-care', 'guard_name' => 'web'],
            ['name' => 'unit', 'guard_name' => 'web'],
        ];

        if ($this->command->confirm('Seed data role? [y|N]', true)) {
            $this->command->getOutput()->createProgressBar(count($roles));
            $this->command->getOutput()->progressStart();
            foreach ($roles as $role) {
                Role::create($role);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
            $this->command->info('Data role inserted to database');
        }
    }
}
