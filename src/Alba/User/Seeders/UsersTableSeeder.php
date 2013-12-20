<?php namespace Alba\User\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Alba\Core\Seeders\Seeder;

/**
 * Seeder for Users and Names
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 */
class UsersTableSeeder extends Seeder {

    public function run() {

        $this->beforeRun();

        // Delete existing records from users table
        // @note: user_names table is cascaded in foreign key relationship
        DB::table("users")->delete();

        /*
         * Array of user configurations with associate keys for user (array), name (array), and role (string)
         *
         * @example
         * $userN = ['user' => array, 'name' => array, 'role' => string]
         * $users = [ $user1, $user2, $userN ]
         */
        $users = [
            
            // Admin user
            [
                'user' => [            
                    "email" => "admin@app.dev",
                    "password" => 'password',
                    "password_confirmation" => 'password',
                    "blocked" => false,
                    "active" => true,
                    'activated_at' => Carbon::now(),
                    'password_updated_at' => Carbon::now(),
                ],
                'name' => [
                    'title' => 'Mr.',
                    'first_name' => 'Admin',
                    'last_name' => 'User',
                    'suffix' => 'PhD'
                ],
                'role' => ['admin']
            ],

            // Common user
            [
                'user' => [            
                    "email" => "user@app.dev",
                    "password" => 'password',
                    "password_confirmation" => 'password',
                    "blocked" => false,
                    "active" => true, 
                    'activated_at' => Carbon::now(),
                    'password_updated_at' => Carbon::now(),
                ],
                'name' => [
                    'title' => 'Mr.',
                    'first_name' => 'Common',
                    'last_name' => 'User',
                    'suffix' => 'MD'
                ],
                'role' => ['user']
            ]
        ];
        
        // Iterate over users saving each to database
        DB::transaction(function() use ($users)
        {
            foreach($users as $arr)
            {
                // Save new user
                $user = new \AlbaUser;
                $user->fill(array_only($arr['user'], $user->getFillable()));
                $this->saveOrFail($user, $user->rulesForSeeding);

                // Assign role to user
                if($arr['role'])
                {
                    foreach ($arr['role'] as $roleName)
                    {
                        $role = \AlbaRole::whereName($roleName)->first();
                        $user->attachRole($role);
                    }
                }

                // Save new name to user
                $name = new \AlbaName();
                $name->fill(array_only($arr['name'], $name->getFillable()));
                $name->user()->associate($user);
                $this->saveOrFail($name);
            }
        });        

        $this->afterRun();

    }

}