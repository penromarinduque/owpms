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
        $user = User::create([
            "email" => "admin@penromarinduque.gov.ph",
            "password" => Hash::make("123456789"),
            "username" => "administrator",
            "usertype" => User::TYPE_ADMIN,
            "is_active_user" => 1
        ]);

        $user->personalInfo()->create([
            "last_name" => "Admin",
            "middlde_name" => "Admin",
            "email" => "admin@penromarinduque.gov.ph",
            "first_name" => "Admin",
            "gender" => "male",
            "contact_no" => "091235467896",
            "barangay_id" => '12708'
        ]);


    }
}
