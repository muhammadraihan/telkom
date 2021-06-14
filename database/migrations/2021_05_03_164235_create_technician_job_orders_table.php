<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechnicianJobOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technician_job_orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('repair_item_uuid')->nullable();
            $table->integer('item_status')->nullable()->comment("0=Butuh Penggantian, 1=Telah diperbaiki oleh teknisi,2=Ticket cancel");
            $table->text('keterangan')->nullable();
            $table->integer('job_status')->nullable()->comment("0=Dalam proses, 1=Selesai, 2=Ticket cancel");
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
        Schema::dropIfExists('technician_job_orders');
    }
}
