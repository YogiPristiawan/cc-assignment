<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' =>  'e61796f8-4aaa-4bfe-b29a-34cf284b4276',
            'name' => 'Nicola Tesla',
            'current_balance' => 0
        ]);
    }
}
