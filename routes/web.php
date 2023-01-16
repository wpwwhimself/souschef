<?php

use App\Http\Controllers\HomeController;
use App\Models\Ingredient;
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

Route::controller(HomeController::class)->group(function(){
    Route::middleware("auth")->group(function(){
        foreach([
            "dashboard",
            "ingredients",
        ] as $name){
            Route::get("/$name", $name)->name($name);
        }
        Route::post("/ingredients/add", "ingredientAdd")->name("ingredient-add");

        Route::get("/ingredients/templates", "ingredientTemplates")->name("ingredient-templates");
        Route::post("/ingredients/templates/add", "ingredientTemplateAdd")->name("ingredient-template-add");
    });
});

/**
 * AJAX things
 */
Route::get("/ajax/ingredient_unit", function(Request $rq){
    return Ingredient::find($rq->ing_id)->unit;
})->name("ajax_ingredient_unit");
