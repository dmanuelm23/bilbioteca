<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
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
        $user = User::create([
            'name' => 'Luis Daniel Manuel Martínez',
            'email' => 'luisdaniel_23@hotmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Hola1234'),
            'remember_token' => Str::random(10),
        ]);
        $user->assignRole('Administrativo');
        $users = User::factory(10)->create()->each(function ($user){
            $user->assignRole('Prestatario');
        });
    }
}
