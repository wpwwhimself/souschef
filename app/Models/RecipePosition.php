<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipePosition extends Model
{
    use HasFactory;

    protected $fillable = [
        "recipe_id",
        "ingredient_template_id",
        "amount"
    ];
    public $timestamps = false;

    public function recipe(){
        return $this->belongsTo(Recipe::class);
    }
    public function template(){
        return $this->belongsTo(IngredientTemplate::class, "ingredient_template_id");
    }
}
