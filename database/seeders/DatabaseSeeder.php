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
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'users menu']);
        Permission::create(['name' => 'users create']);
        Permission::create(['name' => 'users edit']);
        Permission::create(['name' => 'users delete']);

        Permission::create(['name' => 'roles menu']);
        Permission::create(['name' => 'roles add-permission']);
        Permission::create(['name' => 'roles create']);
        Permission::create(['name' => 'roles edit']);
        Permission::create(['name' => 'roles delete']);

        Permission::create(['name' => 'permissions menu']);
        Permission::create(['name' => 'permissions create']);
        Permission::create(['name' => 'permissions edit']);
        Permission::create(['name' => 'permissions delete']);

        // create roles and assign created permissions
        $role = Role::create(['name' => 'Administrator']);
        $role->givePermissionTo(Permission::all());


        // create user
        $user = User::factory()->create([
            'name' => 'SUPER ADMIN',
            'username' => 'supermin',
            'password' => 'super123',
            'email' => 'apps.development@dem.dharmap.com',
            'picture' => 'supermin_avatar.png',
            'organization_id' => 1,
        ]);

        $user->hasRole('Administrator');
        $user->syncRoles($role);

    }
}
