<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('module_category_uuid')->nullable();
            $table->string('module_name_uuid')->nullable();
            $table->string('material_type')->nullable();
            $table->string('material_description')->nullable();
            $table->string('volume')->nullable()->comment('exp= buah,kotak');
            $table->bigInteger('available')->nullable();
            $table->double('unit_price', 9, 2)->nullable();
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
        Schema::dropIfExists('materials');
    }
}
