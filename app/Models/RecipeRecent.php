<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeRecent extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $dates = ["date"];

    protected $fillable = [
        "recipe_id", "date",
    ];

    public function recipe(){
        return $this->belongsTo(Recipe::class);
    }
}
