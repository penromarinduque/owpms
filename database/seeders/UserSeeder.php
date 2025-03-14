<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            "email" => "admin@penromarinduque.gov.ph",
            "password" => Hash::make("*OWPMS_2025*"),
            "username" => "administrator",
            "usertype" => User::TYPE_ADMIN,
            "is_active_user" => 1
        ]);
    }
}
