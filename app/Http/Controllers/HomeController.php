<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\IngredientsChange;
use App\Models\IngredientTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard(){
        $ingredients_count = Ingredient::groupBy("id")
            ->selectRaw("sum(amount) as sum, id")
            ->pluck("sum", "id");
        $shopping_list = IngredientTemplate::withSum("positions", "amount")
            ->get();
        $shopping_list = $shopping_list->filter(function ($x) {
            return $x->minimum_amount > $x->positions_sum_amount;
        });
        $spoiled = Ingredient::whereDate("expiration_date", "<", today())
            ->orderBy("expiration_date")
            ->get();

        return view("dashboard", array_merge(
            ["title" => "Kuchnia"],
            compact("shopping_list", "spoiled")
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
            "minimum_amount" => $rq->minimum_amount,
            "unit" => $rq->unit ?? "JNO",
            "ingredient_category_id" => $rq->ingredient_category_id,
        ]);
        return redirect()->route("ingredients")->with("success", "Dodano wzorzec");
    }

    public function ingredients(){
        $cupboard_raw = Ingredient::whereHas("template", function($q){
            return $q->whereIn("ingredient_category_id", [1, 7, 8]);
        })
            ->join("ingredient_templates", "ingredients.ingredient_template_id", "ingredient_templates.id")
            ->orderBy("name")
            ->orderByDesc("amount")
            ->get();
        $fridge_raw = Ingredient::whereHas("template", function($q){
            return $q->whereIn("ingredient_category_id", [2, 3, 4, 5, 6, 9, 10]);
        })
            ->join("ingredient_templates", "ingredients.ingredient_template_id", "ingredient_templates.id")
            ->orderBy("name")
            ->orderByDesc("amount")
            ->get();

        $cupboard = []; $fridge = [];
        foreach($cupboard_raw as $ingredient){ $cupboard[$ingredient->ingredient_category_id][] = $ingredient; }
        foreach($fridge_raw as $ingredient){ $fridge[$ingredient->ingredient_category_id][] = $ingredient; }
        $categories = IngredientCategory::all()->pluck("name", "id");

        $templates = IngredientTemplate::orderBy("name")->get()->pluck("name", "id")->toArray();

        $changes = IngredientsChange::orderByDesc("id")->limit(10)->get();

        return view("ingredients", array_merge(
            ["title" => "Co mamy pod ręką?"],
            compact("cupboard", "fridge", "templates", "changes", "categories")
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

        IngredientsChange::create([
            "ingredient_template_id" => $rq->ingredient_template_id,
            "amount" => $rq->amount,
        ]);

        return back()->with("success", "Dodano składnik");
    }
}
