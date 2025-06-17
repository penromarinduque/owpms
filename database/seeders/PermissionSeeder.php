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
                'permission_tag' => 'PERMITTEE_INDEX',
                'description' => 'Viewing of Permittees',
                'permission_group' => 'PERMITTEE'
            ],
            [
                'permission_tag' => 'PERMITTEE_CREATE',
                'description' => 'Creation of Permittees',
                'permission_group' => 'PERMITTEE'
            ],
            [
                'permission_tag' => 'PERMITTEE_UPDATE',
                'description' => 'Update of Permittees',
                'permission_group' => 'PERMITTEE'
            ],

            // PERMITTEE SPECIES
            [
                'permission_tag' => 'PERMITTEE_SPECIE_INDEX',
                'description' => 'Viewing of Permittee Species',
                'permission_group' => 'PERMITTEE_SPECIES'
            ],
            [
                'permission_tag' => 'PERMITTEE_SPECIE_CREATE',
                'description' => 'Creation of Permittee Species',
                'permission_group' => 'PERMITTEE_SPECIES'
            ],
            [
                'permission_tag' => 'PERMITTEE_SPECIE_UPDATE',
                'description' => 'Update of Permittee Species',
                'permission_group' => 'PERMITTEE_SPECIES'
            ],
            [
                'permission_tag' => 'PERMITTEE_SPECIE_DELETE',
                'description' => 'Delete of Permittee Species',
                'permission_group' => 'PERMITTEE_SPECIES'
            ],

            // LTP APPLICATION
            [
                'permission_tag' => 'LTP_APPLICATION_INDEX',
                'description' => 'Viewing of LTP Application',
                'permission_group' => 'LTP_APPLICATION'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_CREATE',
                'description' => 'Creation of LTP Application',
                'permission_group' => 'LTP_APPLICATION'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_UPDATE',
                'description' => 'Update of LTP Application',
                'permission_group' => 'LTP_APPLICATION'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_DELETE',
                'description' => 'Delete of LTP Application',
                'permission_group' => 'LTP_APPLICATION'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_PREVIEW',
                'description' => 'LTP Application Preview',
                'permission_group' => 'LTP_APPLICATION'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_REVIEW',
                'description' => 'LTP Application Review',
                'permission_group' => 'LTP_APPLICATION'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_PRINT',
                'description' => 'LTP Application Print',
                'permission_group' => 'LTP_APPLICATION'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_ACCEPT',
                'description' => 'LTP Application Accept',
                'permission_group' => 'LTP_APPLICATION'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_RETURN',
                'description' => 'LTP Application Return',
                'permission_group' => 'LTP_APPLICATION'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_APPROVE',
                'description' => 'LTP Application Approve',
                'permission_group' => 'LTP_APPLICATION'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_INSPECT',
                'description' => 'LTP Application Inspect',
                'permission_group' => 'LTP_APPLICATION'
            ],
            [
                'permission_tag' => 'LTP_APPLICATION_RELEASE',
                'description' => 'An action authorizing the releasing of Local Transport Permits',
                'permission_group' => 'LTP_APPLICATION'
            ],

            // WILDLIFE TYPES
            [
                'permission_tag' => 'WILDLIFE_TYPE_INDEX',
                'description' => 'View List of Wildlife Types',
                'permission_group' => 'WILDLIFE_TYPE'
            ],
            [
                'permission_tag' => 'WILDLIFE_TYPE_CREATE',
                'description' => 'Creation of Wildlife Types',
                'permission_group' => 'WILDLIFE_TYPE'
            ],
            [
                'permission_tag' => 'WILDLIFE_TYPE_UPDATE',
                'description' => 'Update of Wildlife Types',
                'permission_group' => 'WILDLIFE_TYPE'
            ],

            // CLASS
            [
                'permission_tag' => 'CLASS_INDEX',
                'description' => 'Viewing of Class',
                'permission_group' => 'CLASS'
            ],
            [
                'permission_tag' => 'CLASS_CREATE',
                'description' => 'Creation of Class',
                'permission_group' => 'CLASS'
            ],
            [
                'permission_tag' => 'CLASS_UPDATE',
                'description' => 'Update of Class',
                'permission_group' => 'CLASS'
            ],

            // FAMILY
            [
                'permission_tag' => 'FAMILY_INDEX',
                'description' => 'Viewing of Family',
                'permission_group' => 'FAMILY'
            ],
            [
                'permission_tag' => 'FAMILY_CREATE',
                'description' => 'Creation of Family',
                'permission_group' => 'FAMILY'
            ],
            [
                'permission_tag' => 'FAMILY_UPDATE',
                'description' => 'Update of Family',
                'permission_group' => 'FAMILY'
            ],

            // SPECIES
            [
                'permission_tag' => 'SPECIES_INDEX',
                'description' => 'Viewing of Species',
                'permission_group' => 'SPECIES'
            ],
            [
                'permission_tag' => 'SPECIES_CREATE',
                'description' => 'Creation of Species',
                'permission_group' => 'SPECIES'
            ],
            [
                'permission_tag' => 'SPECIES_UPDATE',
                'description' => 'Update of Species',
                'permission_group' => 'SPECIES'
            ],

            // LTP REQUIREMENTS
            [
                'permission_tag' => 'LTP_REQUIREMENTS_INDEX',
                'description' => 'Viewing of LTP Requirements',
                'permission_group' => 'LTP_REQUIREMENTS'
            ],
            [
                'permission_tag' => 'LTP_REQUIREMENTS_CREATE',
                'description' => 'Creation of LTP Requirements',
                'permission_group' => 'LTP_REQUIREMENTS'
            ],
            [
                'permission_tag' => 'LTP_REQUIREMENTS_UPDATE',
                'description' => 'Update of LTP Requirements',
                'permission_group' => 'LTP_REQUIREMENTS'
            ],

            // USERS
            [
                'permission_tag' => 'USER_INDEX',
                'description' => 'Viewing of Users',
                'permission_group' => 'USERS'
            ],
            [
                'permission_tag' => 'USER_CREATE',
                'description' => 'Creation of Users',
                'permission_group' => 'USERS'
            ],
            [
                'permission_tag' => 'USER_UPDATE',
                'description' => 'Update of Users',
                'permission_group' => 'USERS'
            ],

            // POSITIONS
            [
                'permission_tag' => 'POSITION_INDEX',
                'description' => 'Viewing of Positions',
                'permission_group' => 'POSITIONS'
            ],
            [
                'permission_tag' => 'POSITION_CREATE',
                'description' => 'Creation of Positions',
                'permission_group' => 'POSITIONS'
            ],
            [
                'permission_tag' => 'POSITION_UPDATE',
                'description' => 'Update of Positions',
                'permission_group' => 'POSITIONS'
            ],

            // LTP FEES
            [
                'permission_tag' => 'LTP_FEES_INDEX',
                'description' => 'Creation of LTP Fees',
                'permission_group' => 'LTP_FEES'
            ],
            [
                'permission_tag' => 'LTP_FEES_CREATE',
                'description' => 'Creation of LTP Fees',
                'permission_group' => 'LTP_FEES'
            ],
            [
                'permission_tag' => 'LTP_FEES_UPDATE',
                'description' => 'Update of LTP Fees',
                'permission_group' => 'LTP_FEES'
            ],
            [
                'permission_tag' => 'LTP_FEES_DELETE',
                'description' => 'Delete of LTP Fees',
                'permission_group' => 'LTP_FEES'
            ],

            // ROLES
            [
                'permission_tag' => 'ROLES_CREATE',
                'description' => 'Creation of Roles',
                'permission_group' => 'ROLES'
            ],
            [
                'permission_tag' => 'ROLES_UPDATE',
                'description' => 'Update of Roles',
                'permission_group' => 'ROLES'
            ],
            [
                'permission_tag' => 'ROLES_INDEX',
                'description' => 'View the list of Roles',
                'permission_group' => 'ROLES'
            ],
            [
                'permission_tag' => 'ROLES_DELETE',
                'description' => 'An action authorizinf the deletion of roles.',
                'permission_group' => 'ROLES'
            ],

            // USER_ROLES
            [
                'permission_tag' => 'USER_ROLES_INDEX',
                'description' => 'Viewing of User Roles',
                'permission_group' => 'USER_ROLES'
            ],
            [
                'permission_tag' => 'USER_ROLES_CREATE',
                'description' => 'Creation of User Roles',
                'permission_group' => 'USER_ROLES'
            ],
            [
                'permission_tag' => 'USER_ROLES_UPDATE',
                'description' => 'Update of User Roles',
                'permission_group' => 'USER_ROLES'
            ],
            [
                'permission_tag' => 'USER_ROLES_DELETE',
                'description' => 'Delete of User Roles',
                'permission_group' => 'USER_ROLES'
            ],

            // PAYMENT ORDERS
            [
                'permission_tag' => 'PAYMENT_ORDERS_INDEX',
                'description' => 'Viewing of Payment Orders',
                'permission_group' => 'PAYMENTS'
            ],
            [
                'permission_tag' => 'PAYMENT_ORDERS_CREATE',
                'description' => 'Creation of Payment Orders',
                'permission_group' => 'PAYMENTS'
            ],
            [
                'permission_tag' => 'PAYMENT_ORDERS_UPDATE',
                'description' => 'Update of Payment Orders',
                'permission_group' => 'PAYMENTS'
            ],
            [
                'permission_tag' => 'PAYMENT_ORDERS_DELETE',
                'description' => 'Delete of Payment Orders',
                'permission_group' => 'PAYMENTS'
            ],
            // ISSUED OR
            [
                'permission_tag' => 'ISSUED_OR_INDEX',
                'description' => 'An action authorizing the viewing of list of Issued OR.',
                'permission_group' => 'PAYMENTS'
            ],

            // SIGNATORIES
            [
                'permission_tag' => 'SIGNATORIES_INDEX',
                'description' => 'Viewing of Signatories',
                'permission_group' => 'SIGNATORIES'
            ],
            [
                'permission_tag' => 'SIGNATORIES_CREATE',
                'description' => 'Creation of Signatories',
                'permission_group' => 'SIGNATORIES'
            ],
            [
                'permission_tag' => 'SIGNATORIES_UPDATE',
                'description' => 'Update of Signatories',
                'permission_group' => 'SIGNATORIES'
            ],
            [
                'permission_tag' => 'SIGNATORIES_DELETE',
                'description' => 'Delete of Signatories',
                'permission_group' => 'SIGNATORIES'
            ],

        ];

        Permission::insert($permissions);
    }
}
