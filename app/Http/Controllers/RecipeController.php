<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
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

        return view("recipe", array_merge(
            ["title" => "Przepis na $recipe->name"],
            compact("recipe")
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
        //TODO uzupełnić odbijanie przepisu
    }
}
