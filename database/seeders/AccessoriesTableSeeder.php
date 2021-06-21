<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accessory;
use App\Models\User;

class AccessoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', '=', 'superadmin')->first()->uuid;
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
    }
}
