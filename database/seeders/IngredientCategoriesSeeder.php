<?php

namespace Database\Seeders;

use App\Models\IngredientCategory;
use Illuminate\Database\Seeder;

class IngredientCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
