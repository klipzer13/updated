<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'Admin', 'description' => 'Administrator']);
        $developerRole = Role::create(['name' => 'Chairperson', 'description' => 'Chairperson']);
        $managerRole = Role::create(['name' => 'Employee', 'description' => 'Employee']);
        
        // Create departments
        $itDept = Department::create(['name' => 'IT', 'description' => 'Information Technology']);
        $hrDept = Department::create(['name' => 'HR', 'description' => 'Human Resources']);
        $financeDept = Department::create(['name' => 'Finance', 'description' => 'Finance Department']);
        
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin12345'),
            'username' => 'admin',
            'role_id' => $adminRole->id,
            'department_id' => $itDept->id,
            'avatar' => 'storage/profile/avatars/profile.png'
        ]);
        
    }
}