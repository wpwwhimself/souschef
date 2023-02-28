<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientsChange;
use App\Models\IngredientTemplate;
use App\Models\Recipe;
use App\Models\RecipePosition;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    function recipes(){
        $recipes = Recipe::all();

        return view("recipes", array_merge(
            ["title" => "Co gotujemy?"],
            compact("recipes")
        ));
    }

    function recipe($recipe_id){
        $recipe = Recipe::findOrFail($recipe_id);
        $can_cook_recipe = true;
        foreach($recipe->ingredients as $requirement){
            $available[$requirement->ingredient_template_id] = $requirement->template->positions->sum("amount");
            if($available[$requirement->ingredient_template_id] < $requirement->amount) $can_cook_recipe = false;
        }

        return view("recipe", array_merge(
            ["title" => "Przepis na $recipe->name"],
            compact("recipe", "available", "can_cook_recipe")
        ));
    }

    function add(){
        $templates = IngredientTemplate::orderBy("name")->pluck("name", "id");

        return view("recipe-add", array_merge(
            ["title" => "Dodaję nowy przepis"],
            compact("templates")
        ));
    }

    function process(Request $rq){
        $recipe = Recipe::create([
            "name" => $rq->name,
            "desc" => $rq->desc,
            "for_dinner" => $rq->has("for_dinner"),
            "for_supper" => $rq->has("for_supper"),
        ]);

        RecipePosition::insert(
            array_filter(
                array_map(
                    function($ingredient, $amount) use($recipe){
                        return [
                            "ingredient_template_id" => $ingredient,
                            "amount" => $amount,
                            "recipe_id" => $recipe->id,
                        ];
                    },
                    $rq->ingredient_template_id,
                    $rq->amount
                ),
                function($x){
                    return $x["ingredient_template_id"] !== null;
                }
            )
        );

        return redirect()->route("recipes")->with("success", "Przepis dodany");
    }

    public function clear($recipe_id, Request $rq){
        foreach($rq->except(["_token"]) as $ingredient_template_id => $amount_to_sub){
            $ingredients_to_sub_from = Ingredient::where("ingredient_template_id", $ingredient_template_id)
                ->orderByRaw("case when expiration_date is null then 1 else 0 end")
                ->orderBy("expiration_date")
                ->get()
                ;

            $i = 0; $amount_left = $amount_to_sub;
            while($amount_left > 0){
                $current_amount_to_sub = min($amount_left, $ingredients_to_sub_from[$i]);
                $ingredients_to_sub_from[$i]->amount -= $current_amount_to_sub;
                $ingredients_to_sub_from[$i]->save();
                app("App\Http\Controllers\HomeController")->ingredientsCleanup();
                $amount_left -= $current_amount_to_sub; $i++;
            }

            IngredientsChange::create([
                "ingredient_template_id" => $ingredient_template_id,
                "amount" => -$amount_to_sub,
            ]);
        }

        return redirect()->route("ingredients")->with("success", "Stany pomniejszone");
    }
}
