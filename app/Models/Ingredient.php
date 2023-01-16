<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "unit", "ingredient_category_id",
    ];
    public $timestamps = false;

    public function category(){
        return $this->belongsTo(IngredientCategory::class, 'ingredient_category_id');
    }
    public function positions(){
        return $this->hasMany(IngredientPosition::class);
    }
}
