<?php

use App\Http\Controllers\HomeController;
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
        foreach([
            "ingredients-add",
        ] as $name){
            Route::post("/$name", Str::camel($name))->name($name);
        }
    });
});