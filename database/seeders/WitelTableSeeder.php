<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Witel;

class WitelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', '=', 'superadmin')->first()->uuid;
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
    }
}
