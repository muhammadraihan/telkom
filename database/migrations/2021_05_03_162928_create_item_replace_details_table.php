<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemReplaceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_replace_details', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->integer('replace_status')->nullable()->comment("3=Stock, 4=Vendor");
            $table->string('vendor_name')->nullable();
            $table->string('item_repair_uuid')->nullable();
            $table->string('module_type_uuid')->nullable();
            $table->string('part_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('serial_number_msc')->nullable();
            $table->json('accessories')->nullable();
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
        Schema::dropIfExists('item_replace_details');
    }
}
