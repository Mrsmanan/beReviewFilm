<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $role = DB::table('roles')->where('name', 'admin')->first();
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('00000000'),
            'role_id' => $role->id,
        ]);
    }
}