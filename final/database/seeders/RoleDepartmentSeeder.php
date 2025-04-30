<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Department;
use Illuminate\Database\Seeder;

class RoleDepartmentSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $roles = [
            ['name' => 'Admin', 'description' => 'Administrator'],
            ['name' => 'Chairperson', 'description' => 'Department Manager'],
            ['name' => 'Employee', 'description' => 'Regular Member'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create departments
        $departments = [
            ['name' => 'IT', 'description' => 'Information Technology'],
            ['name' => 'HR', 'description' => 'Human Resources'],
            ['name' => 'Finance', 'description' => 'Finance Department'],
            ['name' => 'Marketing', 'description' => 'Marketing Team'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}