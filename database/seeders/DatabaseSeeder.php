<?php

namespace Database\Seeders;

use App\Models\IngredientCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if(env("APP_ENV") == "local"){
            User::insert([
                [
                    "name" => "kucharz1",
                    "password" => Hash::make("kalafior")
                ],
                [
                    "name" => "kucharz2",
                    "password" => Hash::make("marchewka")
                ],
            ]);
        }

        $this->call([
            IngredientCategoriesSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
