<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'id'        =>   1,
            'role'      =>  'Admin',
            'name'      =>  'Admin',
            'email'     =>  'admin@gmail.com',
            'phone'     =>  '0000000000',
            'password'  =>  Hash::make(12345678),
            'city'      =>  'Indore',
            'created_at' =>  now(),
        ]);
    }
}
