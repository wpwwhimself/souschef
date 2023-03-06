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

    public function canBeCooked(){
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
