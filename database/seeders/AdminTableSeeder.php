<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->delete();
        $adminRecords = [
            ['id' => 1, 'name' => 'Yogesh', 'type' => 'admin', 'mobile' => '7775091047', 'email' => 'yogeshwalokar786@gmail.com', 'password' => '$2y$12$LwqJ/9/mEP9pXfmM9lpqN.OYbNxH.w2D7ei4vDXxu5arcvM0B2JNy', 'image' => '', 'status' => 1],
        ];


        DB::table('admins')->insert($adminRecords);
    }
}
