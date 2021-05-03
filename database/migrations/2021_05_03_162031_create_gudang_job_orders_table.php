<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGudangJobOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gudang_job_orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('repair_item_uuid')->nullable();
            $table->integer('item_status')->nullable()->comment("1=Butuh perbaikan dari vendor, 2=Menunggu perbaikan dari vendor,
            3=Menunggu penggantian dari vendor, 4=Item telah diperbaiki oleh teknisi, 5=Item telah diganti oleh vendor");
            $table->text('keterangan')->nullable();
            $table->string('item_replace_uuid')->nullable();
            $table->integer('job_status')->nullable()->comment("0=Open, 1=Closed");
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
        Schema::dropIfExists('gudang_job_orders');
    }
}
