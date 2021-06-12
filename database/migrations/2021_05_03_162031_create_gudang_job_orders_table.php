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
            $table->integer('item_status')->nullable()->comment("1=Dalam perbaikan oleh teknisi, 2=Telah diperbaiki oleh teknisi,3=Butuh klaim garansi,4=Dalam perbaikan oleh , 5=Menunggu penggantian dari vendor, 6=Telah di kirim ke customer, 7=Ticket di cancel");
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
