<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SignatoryRole;

class SignatoryRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        SignatoryRole::insert([
            [
                'id' => 1,
                'role' => 'Prepared By',
                'description' => 'The person who prepares the document and signs it.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'role' => 'Approved By',
                'description' => 'The person who approves the document and signs it.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
