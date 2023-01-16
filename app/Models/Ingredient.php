<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        "ingredient_template_id",
        "amount",
        "expiration_date",
    ];
    protected $dates = ["expiration_date"];

    public function template(){
        return $this->belongsTo(IngredientTemplate::class, "ingredient_template_id");
    }
}
