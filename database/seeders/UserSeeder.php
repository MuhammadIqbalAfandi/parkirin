<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'phone' => '',
            'email' => 'admin@parking.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'),
            'role_id' => 1,
        ]);

        // User::factory(199)->create();
    }
}
