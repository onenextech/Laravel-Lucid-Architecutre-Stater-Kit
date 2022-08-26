<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {

        if (app()->environment('local')) {
            // Wipe the tables used for authorization
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                collect(config('permission.table_names'))->values()->each(fn($table) => DB::table($table)->truncate());
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (\Exception $exception) {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                throw $exception;
            }
        }

        // Seed the tables
        $guard = config('auth.defaults.guard');

        collect(config('core.roles'))
            ->map(fn($role) => ['name' => $role, 'guard_name' => $guard])
            ->pipe(fn($roles) => Role::insertOrIgnore($roles->toArray()));

        collect(config('core.permissions'))
            ->map(fn($permission) => ['name' => $permission, 'guard_name' => $guard])
            ->pipe(fn($permissions) => Permission::insertOrIgnore($permissions->toArray()));
    }
}
