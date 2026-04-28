<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('permissions.groups', []);

        foreach ($config as $group => $data) {
            foreach ($data['permissions'] ?? [] as $slug => $name) {
                Permission::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $name, 'group' => $data['label'] ?? $group]
                );
            }
        }

        $superAdmin = Role::firstOrCreate(
            ['slug' => 'super_admin'],
            ['name' => 'مدير النظام', 'description' => 'صلاحيات كاملة']
        );

        $superAdmin->permissions()->sync(Permission::pluck('id'));

        $admin = Admin::where('email', 'admin@admin.com')->first();
        if ($admin) {
            $admin->roles()->syncWithoutDetaching([$superAdmin->id]);
        }
    }
}
