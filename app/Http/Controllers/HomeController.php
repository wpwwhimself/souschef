<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\IngredientPosition;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard(){
        return view("dashboard", array_merge(
            ["title" => "Kuchnia"]
        ));
    }

    public function ingredients(){
        $ingredients = Ingredient::all();
        $ingredient_categories = IngredientCategory::all()->pluck("name", "id")->toArray();

        return view("ingredients", array_merge(
            ["title" => "Lista potencjalnych składników"],
            compact("ingredients", "ingredient_categories")
        ));
    }
    public function ingredientsAdd(Request $rq){
        Ingredient::create([
            "name" => $rq->name,
            "unit" => $rq->unit ?? "szt.",
            "ingredient_category_id" => $rq->ingredient_category_id,
        ]);
        return back()->with("success", "Dodano składnik");
    }

    public function positions(){
        $cupboard = IngredientPosition::whereHas("ingredient", function($q){
            return $q->whereIn("ingredient_category_id", [1, 6, 7, 8]);
        })->get();
        $fridge = IngredientPosition::whereHas("ingredient", function($q){
            return $q->whereIn("ingredient_category_id", [2, 3, 4, 5, 9, 10]);
        })->get();

        $ingredients = Ingredient::all()->pluck("name", "id")->toArray();

        return view("positions", array_merge(
            ["title" => "Co mamy pod ręką?"],
            compact("cupboard", "fridge", "ingredients")
        ));
    }
    public function positionsAdd(Request $rq){
        $target = IngredientPosition::where([
            "ingredient_id" => $rq->ingredient_id,
            "expiration_date" => $rq->expiration_date,
        ])->first();
        if(!empty($target)){
            $target->amount += $rq->amount;
            $target->save();
        }else{
            IngredientPosition::create([
                "ingredient_id" => $rq->ingredient_id,
                "amount" => $rq->amount,
                "expiration_date" => $rq->expiration_date,
            ]);
        }
        return back()->with("success", "Dodano składnik");
    }
}
