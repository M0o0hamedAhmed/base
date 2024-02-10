<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $user = User::query()->create([
           'name' => "Mohamed Ahmed",
           'email' => "mabolaumon@gmail.com",
           'password' => bcrypt('admin'),
       ]);
    }
}
