<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Customer_type;

class JenisPelangganTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', '=', 'superadmin')->first()->uuid;
        $customers = [
            ['name' => 'Corporate', 'created_by' => $user],
            ['name' => 'Retail', 'created_by' => $user],
        ];
        if ($this->command->confirm('Seed data jenis pelanggan? [y|N]', true)) {
            $this->command->getOutput()->createProgressBar(count($customers));
            $this->command->getOutput()->progressStart();
            foreach ($customers as $customer) {
                Customer_type::create($customer);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
            $this->command->info('Data jenis pelanggan inserted to database');
        }
    }
}
