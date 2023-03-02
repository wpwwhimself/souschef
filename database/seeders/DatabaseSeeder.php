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

        IngredientCategory::insert([
            ["name" => "nieokr.❓"],
            ["name" => "nabiał🥛"],
            ["name" => "warzywa🥬"],
            ["name" => "owoce🍏"],
            ["name" => "mrożonki🧊"],
            ["name" => "sosy🫙"],
            ["name" => "makarony🍝"],
            ["name" => "przyprawy🧂"],
            ["name" => "mięso🍖"],
            ["name" => "ryby🐟"],
            ["name" => "pieczywo🍞"],
        ]);

        DB::table("settings")->insert([
            ["name" => "ingredient_categories_cupboard", "value" => "1,7,8,11"],
            ["name" => "ingredient_categories_fridge", "value" => "2,3,4,5,6,9,10"],
        ]);
    }
}
