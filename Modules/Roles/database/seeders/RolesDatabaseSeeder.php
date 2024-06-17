<?php

namespace Modules\Roles\Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        // create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $moderatorRole = Role::firstOrCreate(['name' => 'Moderator']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // create permissions
        $adminDashboardPermission = [];
        $adminDashboardPermission[] = Permission::firstOrCreate(['name' => 'view admin dashboard', 'module' => 'Admin', 'guard_name' => 'web']);
        $adminDashboardPermission[] = Permission::firstOrCreate(['name' => 'delete campaign', 'module' => 'Admin', 'guard_name' => 'web']);

        $modDashboardPermission = [];
        $modDashboardPermission[] = Permission::firstOrCreate(['name' => 'View Admin Dashboard', 'module' => 'Admin', 'guard_name' => 'web']);

        // assign roles permissions
        $adminRole->syncPermissions($adminDashboardPermission);
        $moderatorRole->syncPermissions($modDashboardPermission);

        // clear permission cache
        app()['cache']->forget('spatie.permission.cache');

        // set a default timestamp for the email verified at column
        $eva = Carbon::createFromTimestampUTC('1718461800');

        // set admin role
        $admin = User::firstOrCreate([
                                         'account_level' => 1,
                                         'nickname' => 'SiteAdmin',
                                         'email' => 'admin@rc.lan',
                                         'timezone'      => 'America/New_York',
                                         'remember_token' => "55lQ4JtIoQ",
                                         'email_verified_at' => $eva
                                     ],
                                     [
                                         'password' => Hash::make('password'),
                                     ]);

        $admin->assignRole('Admin');

        // set moderator role
        $admin = User::firstOrCreate([
                                         'account_level' => 2,
                                         'nickname' => 'SiteManager',
                                         'email' => 'manager@rc.lan',
                                         'timezone'      => 'America/New_York',
                                         'remember_token' => "CpdtWqP9c8",
                                         'email_verified_at' => $eva
                                     ],
                                     [
                                         'password' => Hash::make('password'),
                                     ]);

        $admin->assignRole('Moderator');

        // set user role
        $users = User::all(['id', 'account_level']);
        $this->command->getOutput()->progressStart(count($users));
        foreach ($users as $index => $userData) {
            if ($userData['account_level'] == 3) {
                $user = User::find($userData['id']);
                if ($user) {
                    $user->assignRole('User');
                }
            }
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();


    }
}
