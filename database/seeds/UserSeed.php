<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin'),
                'role_id' => User::ADMIN,
                'remember_token' => '',
            ],
        ];

        foreach ($items as $item) {
            User::create($item);
        }

        $limit = 50;
        for($i = 0; $i < $limit; $i++){
            User::create([
                'name' => 'gamer-'.$i,
                'password' => bcrypt('gamer'),
                'email' => 'gamer@gamer.com'.$i,
                'role_id' => User::GAMER,
                'credits' => 100,
                'remember_token' => '',
            ]);
        }



    }
}
