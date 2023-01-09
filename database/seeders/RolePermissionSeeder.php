<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $client         = Role::create(['name' => 'Client']);
        // $userRole       = Role::create(['name'=> 'Editor User']);


        //permission group wise
        $permissionGroups = [
            'admin-dashboard' =>[
                'admin-dashboard.view',
            ],
            'client-dashboard' =>[
                'client-dashboard.view',
            ],
            'users' =>[
                'users.list',
                'users.create',
                'users.show',
                'users.edit',
                'users.update',
                'users.destroy',
            ],

            'roles' =>[
                'roles.list',
                'roles.create',
                'roles.edit',
                'roles.update',
                'roles.destroy',
            ],
            'permissions' =>[
                'permissions.list',
                'permissions.create',
                'permissions.edit',
                'permissions.update',
                'permissions.destroy',
            ],
            'admin-ticket' =>[
                'admin-tickets.list',
                'admin-tickets.create',
                'admin-tickets.edit',
                'admin-tickets.update',
                'admin-tickets.show',
                'admin-tickets.destroy',

            ],
            'client-ticket' =>[
                'client-tickets.list',
                'client-tickets.create',
                'client-tickets.edit',
                'client-tickets.update',
                'client-tickets.show',
                'client-tickets.destroy',
            ],
            'report' =>[
                'report.list',
            ],
            'menu' =>[
                'menu.show',
            ],
            'organization' =>[
                'organization.list',
                'organization.create',
                'organization.edit',
                'organization.update',
                'organization.destroy',
            ],
            'product' =>[
                'product.list',
                'product.create',
                'product.edit',
                'product.update',
                'product.destroy',
            ],
        ];
        $clientPermission = [
            'client-dashboard.view',
            'client-tickets.list',
            'client-tickets.create',
            'client-tickets.edit',
            'client-tickets.update',
            'client-tickets.destroy',
        ];

        //insert the permission and assign it to a role
        foreach ($permissionGroups as $permissionGroupsKey => $permissions) {
           foreach ($permissions as  $permissionName) {
               $permission = Permission::create([
                   'group_name' => $permissionGroupsKey,
                   'name'       => $permissionName,
               ]);

               $superAdminRole->givePermissionTo($permission);
               $permission->assignRole($superAdminRole);

               if (in_array($permissionName, $clientPermission)) {
                    $client->givePermissionTo($permission);
                    $permission->assignRole($client);
               }
           }
        }
    }
}
