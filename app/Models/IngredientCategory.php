<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientCategory extends Model
{
    use HasFactory;

    protected $table = "ingredient_categories";
    public $timestamps = false;

    protected $fillable = [
        "name",
    ];

    public function ingredients(){
        return $this->hasMany(Ingredient::class);
    }
}
