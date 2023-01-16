<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        "ingredient_id",
        "amount",
        "expiration_date",
    ];
    protected $dates = ["expiration_date"];

    public function ingredient(){
        return $this->belongsTo(Ingredient::class);
    }
}
