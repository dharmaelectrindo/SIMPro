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
        Permission::create(['name' => 'user menu']);
        Permission::create(['name' => 'user create']);
        Permission::create(['name' => 'user edit']);
        Permission::create(['name' => 'user delete']);

        Permission::create(['name' => 'role menu']);
        Permission::create(['name' => 'role add-permission']);
        Permission::create(['name' => 'role create']);
        Permission::create(['name' => 'role edit']);
        Permission::create(['name' => 'role delete']);

        Permission::create(['name' => 'permission menu']);
        Permission::create(['name' => 'permission create']);
        Permission::create(['name' => 'permission edit']);
        Permission::create(['name' => 'permission delete']);

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
        ]);

        $user->hasRole('Administrator');
        $user->syncRoles($role);

    }
}
