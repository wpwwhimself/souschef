<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
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

        return view("ingredients", array_merge(
            ["title" => "Zawartość lodówki"],
            compact("ingredients")
        ));
    }

    public function ingredientsAdd(Request $rq){
        //
    }
}
