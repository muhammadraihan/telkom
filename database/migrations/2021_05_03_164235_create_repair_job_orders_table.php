<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairJobOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair_job_orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('repair_item_uuid')->nullable();
            $table->integer('item_status')->nullable()->comment("0=Butuh Penggantian, 1=Telah diperbaiki oleh teknisi,2=Ticket cancel");
            $table->integer('job_status')->nullable()->comment("0=Open, 1=Close, 2=Cancel");
            $table->text('repair_notes')->nullable();
            $table->json('component_used')->nullable();
            $table->double('repair_cost')->nullable();
            $table->double('time_to_repair', 8, 2)->nullable();
            $table->string('assign_to')->nullable();
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
        Schema::dropIfExists('repair_job_orders');
    }
}
