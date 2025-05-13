<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $permissions = [
            // PERMITTEE
            [
                'permission_tag' => 'PERMITTEE_CREATE',
                'description' => 'Creation of Permittees'
            ],
            [
                'permission_tag' => 'PERMITTEE_UPDATE',
                'description' => 'Update of Permittees'
            ],

            // PERMITTEE SPECIES
            [
                'permission_tag' => 'PERMITTEE_SPECIE_CREATE',
                'description' => 'Creation of Permittee Species'
            ],
            [
                'permission_tag' => 'PERMITTEE_SPECIE_UPDATE',
                'description' => 'Update of Permittee Species'
            ],
            [
                'permission_tag' => 'PERMITTEE_SPECIE_DELETE',
                'description' => 'Delete of Permittee Species'
            ],

            // LTP APPLICATION
            [
                'permission_tag' => 'LTP_APPLICATION_CREATE',
                'description' => 'Creation of LTP Application'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_UPDATE',
                'description' => 'Update of LTP Application'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_DELETE',
                'description' => 'Delete of LTP Application'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_PREVIEW',
                'description' => 'LTP Application Preview'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_REVIEW',
                'description' => 'LTP Application Review'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_PRINT',
                'description' => 'LTP Application Print'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_ACCEPT',
                'description' => 'LTP Application Accept'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_RETURN',
                'description' => 'LTP Application Return'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_APPROVE',
                'description' => 'LTP Application Approve'
            ],

            // WILDLIFE TYPES
            [
                'permission_tag' => 'WILDLIFE_TYPE_CREATE',
                'description' => 'Creation of Wildlife Types'
            ],
            [
                'permission_tag' => 'WILDLIFE_TYPE_UPDATE',
                'description' => 'Update of Wildlife Types'
            ],

            // CLASS
            [
                'permission_tag' => 'CLASS_CREATE',
                'description' => 'Creation of Class'
            ],
            [
                'permission_tag' => 'CLASS_UPDATE',
                'description' => 'Update of Class'
            ],

            // FAMILY
            [
                'permission_tag' => 'FAMILY_CREATE',
                'description' => 'Creation of Family'
            ],
            [
                'permission_tag' => 'FAMILY_UPDATE',
                'description' => 'Update of Family'
            ],

            // SPECIES
            [
                'permission_tag' => 'SPECIES_CREATE',
                'description' => 'Creation of Species'
            ],
            [
                'permission_tag' => 'SPECIES_UPDATE',
                'description' => 'Update of Species'
            ],

            // LTP REQUIREMENTS
            [
                'permission_tag' => 'LTP_REQUIREMENTS_CREATE',
                'description' => 'Creation of LTP Requirements'
            ],
            [
                'permission_tag' => 'LTP_REQUIREMENTS_UPDATE',
                'description' => 'Update of LTP Requirements'
            ],

            // USERS
            [
                'permission_tag' => 'USER_CREATE',
                'description' => 'Creation of Users'
            ],
            [
                'permission_tag' => 'USER_UPDATE',
                'description' => 'Update of Users'
            ],

            // POSITIONS
            [
                'permission_tag' => 'POSITION_CREATE',
                'description' => 'Creation of Positions'
            ],
            [
                'permission_tag' => 'POSITION_UPDATE',
                'description' => 'Update of Positions'
            ],

            // LTP FEES
            [
                'permission_tag' => 'LTP_FEES_CREATE',
                'description' => 'Creation of LTP Fees'
            ],
            [
                'permission_tag' => 'LTP_FEES_UPDATE',
                'description' => 'Update of LTP Fees'
            ],
            [
                'permission_tag' => 'LTP_FEES_DELETE',
                'description' => 'Delete of LTP Fees'
            ]



        ];

        Permission::insert($permissions);
    }
}
