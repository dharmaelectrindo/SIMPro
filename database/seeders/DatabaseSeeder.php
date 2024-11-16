<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'users menu',
            'users create',
            'users edit',
            'users delete',
            'roles menu',
            'roles add-permission',
            'roles create',
            'roles edit',
            'roles delete',
            'permissions menu',
            'permissions create',
            'permissions edit',
            'permissions delete',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create super admin user
        $user = User::factory()->create([
            'name' => 'SUPER ADMIN',
            'username' => 'supermin',
            'password' => bcrypt('super123'),
            'email' => 'apps.development@dem.dharmap.com',
            'picture' => 'supermin_avatar.png',
            'organization_id' => 1,
        ]);

        // Create role and assign permissions
        $role = Role::firstOrCreate(['name' => 'Administrator']);
        $role->givePermissionTo(Permission::all()); // Assign all permissions to the role

        // Assign role to the user
        $user->assignRole($role->name);

        if ($user->hasRole('Administrator')) {
        }
    }

}
