<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemReplacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_replaces', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('item_repair_uuid')->nullable();
            $table->integer('replace_from')->nullable()->comment("1=Vendor, 2=Stock");
            $table->string('item_replace_uuid_from_stock')->nullable();
            $table->string('item_replace_uuid_from_vendor')->nullable();
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
        Schema::dropIfExists('item_replaces');
    }
}
