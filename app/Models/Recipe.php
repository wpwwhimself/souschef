<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "desc",
        "for_dinner", "for_supper",
    ];
    public $timestamps = false;

    public function ingredients(){
        return $this->hasMany(RecipePosition::class);
    }

    public function totalMass(){
        $mass = 0;
        // dd($this->ingredients);
        foreach($this->ingredients as $ingredient){
            $mass += $ingredient->template->mass * $ingredient->amount;
        }
        return $mass;
    }
    public function canBeCooked(){
        if(!count($this->ingredients)) return false;
        
        $can_cook_recipe = true;
        foreach($this->ingredients as $requirement){
            if(
                $requirement->template->positions->sum("amount") < $requirement->amount
                &&
                $requirement->template->minimum_amount !== null
            ) $can_cook_recipe = false;
        }
        return $can_cook_recipe;
    }
    public function ingredientsSufficient(){
        $return = true;
        foreach($this->ingredients as $requirement){
            if(
                $requirement->template->positions->sum("amount") < $requirement->amount
            ) $return = false;
        }
        return $return;
    }
}
