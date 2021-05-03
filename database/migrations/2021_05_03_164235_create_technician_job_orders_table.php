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
            $table->integer('item_status')->nullable()->comment("0=Butuh perbaikan dari vendor, 1=Item telah diperbaiki olej teknisi");
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('technician_job_orders');
    }
}
