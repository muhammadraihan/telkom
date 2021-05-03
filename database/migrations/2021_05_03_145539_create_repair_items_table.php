<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair_items', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('ticket_uuid')->nullable();
            $table->string('item_model')->nullable();
            $table->string('item_merk')->nullable();
            $table->string('item_type')->nullable();
            $table->string('part_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('barcode')->nullable();
            $table->json('kelengkapan')->nullable();
            $table->text('kerusakan')->nullable();
            $table->integer('status_garansi')->nullable()->comment("0=Non warranty, 1=Warranty");
            $table->integer('can_repair')->nullable()->comment("0=Tidak bisa diperbaiki, 1=Bisa diperbaiki");
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
        Schema::dropIfExists('repair_items');
    }
}
