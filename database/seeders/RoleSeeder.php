<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = collect(['view weather', 'create weather', 'edit posts'])
            ->map(fn (string $name) => Permission::findOrCreate($name, 'api'));

        $userRole = Role::findOrCreate('user', 'api');
        $adminRole = Role::findOrCreate('admin', 'api');

        $userRole->syncPermissions(['view weather']);
        $adminRole->syncPermissions($permissions);
    }
}
