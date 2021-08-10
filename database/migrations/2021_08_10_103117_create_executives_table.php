<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecutivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('executives', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
//            $table->foreign('user_id')->references('id')->on('users');
            $table->string('user_id', 100)->unique();
            $table->string('place_id',100);
            /*$table->unsignedBigInteger('place_id');
            $table->foreign('place_id')->references('id')->on('places');*/
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
        Schema::dropIfExists('executives');
    }
}
