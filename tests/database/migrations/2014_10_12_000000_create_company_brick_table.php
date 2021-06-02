<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyBrickTable extends Migration
{
    public function up(): void
    {
        Schema::create('company_brick', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('brick_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_brick');
    }
}
