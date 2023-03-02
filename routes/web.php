<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecipeController;
use App\Models\IngredientTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome', ["title" => null]);
})->name('home');

Route::middleware("auth")->group(function(){
    Route::controller(HomeController::class)->group(function(){
        foreach([
            "dashboard",
            "home",
            "ingredients",
            "settings",
        ] as $name){
            Route::get("/$name", $name)->name($name);
        }
        Route::post("/ingredients/add", "ingredientAdd")->name("ingredient-add");

        Route::get("/ingredients/templates", "ingredientTemplates")->name("ingredient-templates");
        Route::post("/ingredients/templates/add", "ingredientTemplateAdd")->name("ingredient-template-add");

        Route::get("/home", function(){
            return redirect()->route("dashboard");
        });
    });

    Route::controller(RecipeController::class)->group(function(){
        Route::get("/recipes", "recipes")->name("recipes");
        Route::get("/recipes/view/{id}", "recipe")->name("recipe-view");
        Route::get("/recipes/add", "add")->name("recipe-add");
        Route::post("/recipes/process", "process")->name("recipe-process");

        Route::post("/recipes/clear/{id}", "clear")->name("recipe-clear");
    });
});

/**
 * AJAX things
 */
Route::get("/ajax/ingredient_unit", function(Request $rq){
    return IngredientTemplate::find($rq->ing_id)->unit;
})->name("ajax_ingredient_unit");
