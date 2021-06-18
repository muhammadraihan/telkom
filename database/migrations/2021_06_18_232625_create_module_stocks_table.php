<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('module_category_uuid')->nullable();
            $table->string('module_name_uuid')->nullable();
            $table->string('module_brand_uuid')->nullable();
            $table->string('module_type_uuid')->nullable();
            $table->string('part_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('serial_number_msc')->nullable();
            $table->json('accessories')->nullable();
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
        Schema::dropIfExists('module_stocks');
    }
}
