<?php

namespace Database\Seeders;

use App\Models\SpecieNature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecieNatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $natures = [
            [
                'id' => 1,
                'name' => 'Pupae',
                'description' => "N/A"
            ],
            [
                'id' => 2,
                'name' => 'Live',
                'description' => "N/A"
            ],
            [
                'id' => 3,
                'name' => 'Dried',
                'description' => "N/A"
            ],
        ];

        SpecieNature::insert($natures);
    }
}
