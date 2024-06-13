<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=[
        [
            'name'=>'Admin',
            'phone'=>'081392024435',
            'email'=>'admin@gmail.com',
            'password'=>bcrypt('123456'),
        ],
        [
            'name'=>'User',
            'phone'=>'081392024125',
            'email'=>'usern@gmail.com',
            'password'=>bcrypt('123456'),
        ],
    ];
        DB::table('users')->insert($user);
    }
}
