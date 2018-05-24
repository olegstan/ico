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
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => '$2y$10$vc/IAun.ShvPJQU8kdtI2eVEs8L3pwJlEf3aBzhv51ueP9YagoUru',
                'role_id' => User::ADMIN,
                'remember_token' => '',
            ],
        ];

        foreach ($items as $item) {
            User::create($item);
        }

        $limit = 30;
        for($i = 0; $i < $limit; $i++){
            User::create([
                'name' => 'gamer-'.$i,
                'password' => bcrypt('gamer'),
                'email' => 'gamer@gamer.com'.$i,
                'role_id' => User::GAMER,
                'remember_token' => '',
            ]);
        }



    }
}
