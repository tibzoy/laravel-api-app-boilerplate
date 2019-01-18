<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role_admin = Role::where('name','Administrator')->first();
        $role_head = Role::where('name','Head')->first();
        $role_lead = Role::where('name','Lead')->first();
        $role_supervisor = Role::where('name','Supervisor')->first();
        $role_ts = Role::where('name','Technical Specialist')->first();
        $role_staff = Role::where('name','Staff')->first();


        $user = new User();
        $user->name = 'Administrator';
        $user->email = 'admin@test.com';
        $user->password = bcrypt('admin');
        $user->is_active = true;
        $user->activation_token = '';
        $user->save();
        $user->roles()->attach($role_admin);

        $user = new User();
        $user->name = 'Head User';
        $user->email = 'head@test.com';
        $user->password = bcrypt('head');
        $user->is_active = true;
        $user->activation_token = '';
        $user->save();
        $user->roles()->attach($role_head);

        $user = new User();
        $user->name = 'Lead User';
        $user->email = 'lead@test.com';
        $user->password = bcrypt('lead');
        $user->is_active = true;
        $user->activation_token = '';
        $user->save();
        $user->roles()->attach($role_lead);

        $user = new User();
        $user->name = 'Supervisor User';
        $user->email = 'supervisor@test.com';
        $user->password = bcrypt('supervisor');
        $user->is_active = true;
        $user->activation_token = '';
        $user->save();
        $user->roles()->attach($role_supervisor);

        $user = new User();
        $user->name = 'Technical Specialist User';
        $user->email = 'ts@test.com';
        $user->password = bcrypt('ts');
        $user->is_active = true;
        $user->activation_token = '';
        $user->save();
        $user->roles()->attach($role_ts);

        $user = new User();
        $user->name = 'Staff User';
        $user->email = 'staff@test.com';
        $user->password = bcrypt('staff');
        $user->is_active = true;
        $user->activation_token = '';
        $user->save();
        $user->roles()->attach($role_staff);
    }
}
