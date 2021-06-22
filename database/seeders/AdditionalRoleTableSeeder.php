<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;


class AdditionalRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', '=', 'superadmin')->first()->uuid;
        $roles = [
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'admin-tech', 'guard_name' => 'web'],
            ['name' => 'technician', 'guard_name' => 'web'],
            ['name' => 'warehouse', 'guard_name' => 'web'],
            ['name' => 'customer-service', 'guard_name' => 'web'],
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
