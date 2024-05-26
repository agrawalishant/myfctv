<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        $adminRecords = [
            ['id' => 1, 'name' => 'Yogesh', 'mobile' => '7775091047', 'email' => 'yogeshwalokar786@gmail.com', 'password' => '$2y$12$LwqJ/9/mEP9pXfmM9lpqN.OYbNxH.w2D7ei4vDXxu5arcvM0B2JNy', 'gender' => 'Male', 'date_of_birth' => Carbon::createFromFormat('d-m-Y', '23-10-1998')->format('Y-m-d'), 'status' => 1],
        ];


        DB::table('users')->insert($adminRecords);
    }
}
