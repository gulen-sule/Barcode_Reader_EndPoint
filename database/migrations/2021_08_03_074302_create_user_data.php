<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserData extends Migration
{
    public function up()
    {
        Schema::create('user_data', function (Blueprint $table) {
            $table->id();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->bigInteger("id_number")->nullable();
            $table->string("user_photo_path")->nullable();
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
        Schema::dropIfExists('user_data');
    }
}
