<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'username' => 'admin',
                'email' => 'admin@admin.admin',
                'NamaLengkap' => 'haidil',
                'role' => 'Admin',
                'password' => bcrypt('adminadmin'),
                'alamat' => 'bebas',
            ],
            [
                'username' => 'staff',
                'email' => 'staff@staff.staff',
                'NamaLengkap' => 'haidil ke 2',
                'role' => 'Staff',
                'password' => bcrypt('staffstaff'),
                'alamat' => 'bebas',
            ],
        ]);
    }
}
