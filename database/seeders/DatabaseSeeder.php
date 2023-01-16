<?php

namespace Database\Seeders;

use App\Models\IngredientCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
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
        User::insert([
            [
                "name" => "wpww",
                "password" => Hash::make("kalafior")
            ],
            [
                "name" => "julia",
                "password" => Hash::make("marchewka")
            ],
        ]);
        IngredientCategory::insert([
            ["name" => "❓nieokr."],
            ["name" => "🥛nabiał"],
            ["name" => "🥬warzywa"],
            ["name" => "🍏owoce"],
            ["name" => "🧊mrożonki"],
            ["name" => "🫙sosy"],
            ["name" => "🍝makarony"],
            ["name" => "🧂przyprawy"],
            ["name" => "🍖mięso"],
            ["name" => "🐟ryby"],
        ]);
    }
}
