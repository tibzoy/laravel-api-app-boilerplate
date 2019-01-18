<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'Administrator';
        $role->description = 'Administrator';
        $role->save();

        $role = new Role();
        $role->name = 'Head';
        $role->description = 'Head Role';
        $role->save();

        $role = new Role();
        $role->name = 'Lead';
        $role->description = 'Lead Role';
        $role->save();

        $role = new Role();
        $role->name = 'Supervisor';
        $role->description = 'Supervisor Role';
        $role->save();

        $role = new Role();
        $role->name = 'Technical Specialist';
        $role->description = 'Technical Specialist';
        $role->save();

        $role = new Role();
        $role->name = 'Staff';
        $role->description = 'Staff Role';
        $role->save();
    }
}
