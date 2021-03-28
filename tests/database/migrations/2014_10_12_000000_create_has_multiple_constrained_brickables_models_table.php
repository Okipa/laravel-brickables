<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasMultipleConstrainedBrickablesModelsTable extends Migration
{
    public function up(): void
    {
        Schema::create('has_multiple_constrained_brickables_models', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('has_multiple_constrained_brickables_models');
    }
}
