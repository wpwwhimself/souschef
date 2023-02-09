<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientsChange extends Model
{
    use HasFactory;

    protected $fillable = [
        "ingredient_template_id",
        "amount",
        "date",
    ];
    protected $dates = ["date"];
    const CREATED_AT = "date";
    const UPDATED_AT = "date";

    public function template(){
        return $this->belongsTo(IngredientTemplate::class, "ingredient_template_id")
            ->orderBy("name");
    }
}
