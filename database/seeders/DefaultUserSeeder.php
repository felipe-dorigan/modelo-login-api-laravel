<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'webmaster@gmail.com'],
            [
                'name' => 'webmaster',
                'email' => 'webmaster@gmail.com',
                'password' => Hash::make('admin1234'),
            ]
        );
    }
}
