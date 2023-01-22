<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\IngredientTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard(){
        $shopping_list = Ingredient::with("template")
            ->whereRelation("template", "minimum_amount", ">", DB::raw("ingredients.amount"))->get();

        return view("dashboard", array_merge(
            ["title" => "Kuchnia"],
            compact("shopping_list")
        ));
    }

    public function ingredientTemplates(){
        $templates = IngredientTemplate::orderBy("name")->get();
        $categories = IngredientCategory::all()->pluck("name", "id")->toArray();

        return view("templates", array_merge(
            ["title" => "Lista potencjalnych składników"],
            compact("templates", "categories")
        ));
    }
    public function ingredientTemplateAdd(Request $rq){
        IngredientTemplate::create([
            "name" => $rq->name,
            "unit" => $rq->unit ?? "JNO",
            "ingredient_category_id" => $rq->ingredient_category_id,
        ]);
        return redirect()->route("ingredients
        ")->with("success", "Dodano składnik");
    }

    public function ingredients(){
        $cupboard = Ingredient::whereHas("template", function($q){
            return $q->whereIn("ingredient_category_id", [1, 7, 8]);
        })->get();
        $fridge = Ingredient::whereHas("template", function($q){
            return $q->whereIn("ingredient_category_id", [2, 3, 4, 5, 6, 9, 10]);
        })->get();

        $templates = IngredientTemplate::orderBy("name")->get()->pluck("name", "id")->toArray();

        return view("ingredients", array_merge(
            ["title" => "Co mamy pod ręką?"],
            compact("cupboard", "fridge", "templates")
        ));
    }
    public function ingredientAdd(Request $rq){
        $target = Ingredient::where([
            "ingredient_template_id" => $rq->ingredient_template_id,
            "expiration_date" => $rq->expiration_date,
        ])->first();
        if(!empty($target)){
            $target->amount += $rq->amount;
            $target->save();
            if($target->amount <= 0) $target->delete();
        }else{
            Ingredient::create([
                "ingredient_template_id" => $rq->ingredient_template_id,
                "amount" => $rq->amount,
                "expiration_date" => $rq->expiration_date,
            ]);
        }
        return back()->with("success", "Dodano składnik");
    }
}
