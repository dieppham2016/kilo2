<?php

use Illuminate\Database\Seeder;
use App\Laravue\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		$admin = \App\Laravue\Models\User::create(
			[
				'name' => 'Admin',
				'username' => 'admin',
				'password' => \Illuminate\Support\Facades\Hash::make('nkid@123')
			]
		);
		$moderator = \App\Laravue\Models\User::create(
			[
				'name' => 'Moderator',
				'username' => 'moderator',
				'password' => \Illuminate\Support\Facades\Hash::make('nkid@123')
			]
		);
		$staff = \App\Laravue\Models\User::create(
			[
				'name' => 'Staff',
				'username' => 'staff',
				'password' => \Illuminate\Support\Facades\Hash::make('nkid@123')
			]
		);

        $adminRole = Role::findByName(\App\Laravue\Acl::ROLE_ADMIN);
		$moderatorRole = Role::findByName(\App\Laravue\Acl::ROLE_MODERATOR);
		$staffRole = Role::findByName(\App\Laravue\Acl::ROLE_STAFF);

        $admin->syncRoles($adminRole);
        $moderator->syncRoles($moderatorRole);
        $staff->syncRoles($staffRole);
    }
}
