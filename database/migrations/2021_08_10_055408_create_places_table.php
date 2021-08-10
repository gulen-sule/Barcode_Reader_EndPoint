<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::create('places',function (Blueprint $table){
         $table->id();
         $table->string('name')->nullable(false);
         $table->text('address')->nullable(true);
         $table->string('telephone_number')->nullable(true);
         $table->uuid('uuid')->unique()->nullable(false);
         $table->timestamps();
     });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
