<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = ModelsUser::create([
            'name' => 'ahmed edress',
            'email' => 'ahmededress111@gmail.com',
            'password' => bcrypt('123456'),
            'roles_name' => implode(',', ["owner"]),
            'Status' => 'مفعل',
            ]);
            $role = Role::create(['name' => 'owner']);
            
            $permissions = Permission::pluck('id','id')->all();
            
            $role->syncPermissions($permissions);
            
            $user->assignRole([$role->id]);
            
    }
}