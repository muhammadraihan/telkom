<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Witel;
use App\Models\Unit;
use App\Models\User;


class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', '=', 'superadmin')->first()->uuid;
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
    }
}
