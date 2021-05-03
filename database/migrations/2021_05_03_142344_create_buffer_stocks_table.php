<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBufferStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buffer_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('stock_item_uuid')->nullable();
            $table->bigInteger('buffer_ammount')->nullable();
            $table->string('office_city')->nullable();
            $table->string('created_by')->nullable();
            $table->string('edited_by')->nullable();
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
        Schema::dropIfExists('buffer_stocks');
    }
}
