<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarcodeQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcode_queries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('status')->nullable(false);
            $table->string('user_uuid', 40)->nullable(true);
            $table->uuid('uuid')->nullable(false)->unique();
            $table->integer('expire_time');
            $table->integer('valid_time');
            $table->string('controller_uuid')->nullable(false);
            $table->string('place_uuid')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barcode_queries');
    }
}
