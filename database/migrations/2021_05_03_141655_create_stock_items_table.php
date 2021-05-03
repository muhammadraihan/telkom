<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('item_model')->nullable();
            $table->string('item_merk')->nullable();
            $table->string('item_type')->nullable();
            $table->string('part_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('barcode')->nullable();
            $table->json('kelengkapan')->nullable();
            $table->bigInteger('amount')->nullable();
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
        Schema::dropIfExists('stock_items');
    }
}
