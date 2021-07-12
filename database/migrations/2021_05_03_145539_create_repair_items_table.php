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
            $table->string('module_type_uuid')->nullable();
            $table->string('part_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('serial_number_msc')->nullable();
            $table->json('accessories')->nullable();
            $table->text('complain')->nullable();
            $table->integer('warranty_status')->nullable()->comment("0=Non warranty, 1=Warranty");
            $table->integer('status')->nullable()->comment("1=Diperbaiki oleh teknisi, 2=Diperbaiki oleh vendor,3=Diganti dari Stock, 4= Diganti oleh Vendor");
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
