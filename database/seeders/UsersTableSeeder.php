<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DateTime;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->firstName = "Pascal";
        $user->lastName = "Gaßner";
        $user->password = bcrypt('secret');
        $user->email = "pg@gmail.com";
        $user->save();

        $user2 = new User;
        $user2->firstName = "Karli";
        $user2->lastName = "Mittermayr";
        $user2->password = bcrypt('secret');
        $user2->email = "km@gmail.com";
        $user2->save();

        $user3 = new User;
        $user3->firstName = "Johannes";
        $user3->lastName = "Schönböck";
        $user3->password = bcrypt('secret');
        $user3->email = "js@gmail.com";
        $user3->save();
    }
}
