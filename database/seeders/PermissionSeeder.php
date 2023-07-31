<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\UserAccess;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'dashboard'             => 'dashboard',
            'activities'            => [
                'data entry'        => 'data entry',
                'data list'         => 'data list',
                'picture entry'     => 'picture entry',
                'picture list'      => 'picture list',
            ],

            'settings'              => [
                'company content'   => 'company content',
                'area entry'        => 'area entry',
            ],

            'administration'        => [
                'user register'     => 'user register',
                'user list'         => 'user list',
            ]

        ];

        foreach ($permissions as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    Permission::create([
                        'group_name' => $key,
                        'permissions' => $key . '.' . $value2,
                    ]);
                }
            } else {
                Permission::create([
                    'group_name' => $key,
                    'permissions' => $value,
                ]);
            }
        }

        $allPermissions = Permission::all();
        foreach ($allPermissions as $key => $perm) {
            UserAccess::create([
                'user_id'     => 1,
                'group_name'  => $perm->group_name,
                'permissions' => $perm->permissions,
            ]);
        }
    }
}
