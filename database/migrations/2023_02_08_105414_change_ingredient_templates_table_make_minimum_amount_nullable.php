<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIngredientTemplatesTableMakeMinimumAmountNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("ingredient_templates", function (Blueprint $table) {
            $table->float("minimum_amount")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ingredient_templates', function (Blueprint $table) {
            $table->float("minimum_amount")->default(0)->change();
        });
    }
}
