<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("settings")->insert([
            [
                "name" => "ingredient_categories_cupboard",
                "desc" => "Kategorie produktów wyświetlanych w szafce",
                "value" => "1,7,8,11"
            ],
            [
                "name" => "ingredient_categories_fridge",
                "desc" => "Kategorie produktów wyświetlanych w lodówce",
                "value" => "2,3,4,5,6,9,10"
            ],
            [
                "name" => "recipes_ignore_recents",
                "desc" => "Ile ostatnio ugotowanych przepisów ignorować w sugestiach",
                "value" => "2"
            ],
        ]);
    }
}
