<?php

namespace Database\Seeders;

use App\Repositories\UserRepository;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FakeUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRepository = new UserRepository();
        $faker = Factory::create();
        for ($i = 0; $i < 15; $i++) {
            $first_name = $faker->firstName;
            $last_name = $faker->lastName;
            $login = $first_name . "_" . $last_name;

            $userRepository->create([
                'login' => $login,
                'first_name' => $first_name,
                'email' => $faker->email,
                'last_name' => $last_name,
                'bdate' => $faker->date,
                'exp' => rand(2, 50),
                'password' => Hash::make($faker->password)
            ]);
        }

        echo "Done!\n";
    }
}
