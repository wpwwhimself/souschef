<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\IngredientsChange;
use App\Models\IngredientTemplate;
use App\Models\Recipe;
use App\Models\RecipePosition;
use App\Models\RecipeRecent;
use Illuminate\Http\Request;
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
            return ($x->minimum_amount > $x->positions_sum_amount) && ($x->minimum_amount !== null);
        });
        $spoiled = Ingredient::whereDate("expiration_date", "<", today()->addDays(3))
            ->orderBy("expiration_date")
            ->get();

        $recipe_recents = RecipeRecent::orderByDesc("date")
            ->limit(DB::table("settings")->where("name", "recipes_ignore_recents")->value("value"))
            ->pluck("recipe_id")
            ->toArray();
        $recipe_suggestions_raw = Recipe::all()
            ->filter(function($q){ return $q->canBeCooked(); })
            ->except($recipe_recents);
        $recipe_suggestions["dinner"] = (count($recipe_suggestions_raw->where("for_dinner", true))) ?
            $recipe_suggestions_raw->where("for_dinner", true)->random() :
            null;
        $recipe_suggestions["supper"] = (count($recipe_suggestions_raw->where("for_supper", true))) ?
            $recipe_suggestions_raw->where("for_supper", true)->except($recipe_suggestions["dinner"]?->id)?->random() :
            null;

        return view("dashboard", array_merge(
            ["title" => "Kuchnia"],
            compact("shopping_list", "spoiled", "recipe_suggestions")
        ));
    }

    public function settings(){
        $settings = DB::table("settings")->get();

        return view("settings", array_merge(
            ["title" => "Bezpieczniki kuchnii"],
            compact("settings")
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
        IngredientTemplate::updateOrCreate([
            "id" => $rq->id,
        ],
        [
            "name" => $rq->name,
            "minimum_amount" => $rq->minimum_amount,
            "unit" => $rq->unit ?? "JNO",
            "mass" => $rq->mass ?? 0,
            "ingredient_category_id" => $rq->ingredient_category_id,
        ]);
        return redirect()->route("ingredients")->with("success", "Dodano wzorzec");
    }
    public function ingredientTemplateDelete($id = null){
        if(!$id) return back()->with("error", "Nie wskazano, co usunąć");

        $template = IngredientTemplate::findOrFail($id);

        RecipePosition::where("ingredient_template_id", $id)->delete();
        IngredientsChange::where("ingredient_template_id", $id)->delete();
        Ingredient::where("ingredient_template_id", $id)->delete();
        $template->delete();
        
        return back()->with("success", "Usunięto składnik ".$template->name);
    }

    public function ingredients(){
        $categ_split = DB::table("settings")
            ->whereIn("name", [
                "ingredient_categories_cupboard",
                "ingredient_categories_fridge"
            ])
            ->get()->pluck("value", "name");

        $cupboard_raw = Ingredient::whereHas("template", function($q) use ($categ_split){
            return $q->whereIn("ingredient_category_id", explode(",", $categ_split["ingredient_categories_cupboard"]));
        })
            ->join("ingredient_templates", "ingredients.ingredient_template_id", "ingredient_templates.id")
            ->orderBy("name")
            ->orderByDesc("amount")
            ->select(["ingredients.id", "ingredients.ingredient_template_id", "amount", "expiration_date", "name", "minimum_amount", "unit", "ingredient_category_id"])
            ->get();
        $fridge_raw = Ingredient::whereHas("template", function($q) use ($categ_split){
            return $q->whereIn("ingredient_category_id", explode(",", $categ_split["ingredient_categories_fridge"]));
        })
            ->join("ingredient_templates", "ingredients.ingredient_template_id", "ingredient_templates.id")
            ->orderBy("name")
            ->orderByDesc("amount")
            ->select(["ingredients.id", "ingredients.ingredient_template_id", "amount", "expiration_date", "name", "minimum_amount", "unit", "ingredient_category_id"])
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
            $amount_before = $target->amount;
            $target->amount += $rq->amount ?? 0;
            if($target->template->unit === "JNO"){
                $target->amount = floor($target->amount) + $rq->jno_rem;
            }
            $target->save();
            $this->ingredientsCleanup();
        }else{
            $amount_before = 0;
            Ingredient::create([
                "ingredient_template_id" => $rq->ingredient_template_id,
                "amount" => $rq->amount,
                "expiration_date" => $rq->expiration_date,
            ]);
        }

        IngredientsChange::create([
            "ingredient_template_id" => $rq->ingredient_template_id,
            "amount" => ($target?->amount) - $amount_before,
        ]);

        return back()->with("success", "Dodano składnik");
    }

    public function ingredientsCleanup(){
        Ingredient::where("amount", "<=", 0)->delete();
    }
}
