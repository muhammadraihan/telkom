<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseJobOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_job_orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('repair_item_uuid')->nullable();
            $table->integer('urgent_status')->nullable()->comment('0=non urgent, 1=urgent');
            $table->integer('item_status')->nullable()->comment("1=Dalam penanganan oleh teknisi, 2=Telah diperbaiki oleh teknisi, 3=Tidak dapat diperbaiki teknisi,4=Butuh klaim garansi, 5=Proses klaim garansi,6=Selesai Penggantian module ,7=Dalam penanganan oleh vendor, 8=Selesai penanganan dari vendor, 9=Telah di kirim ke customer, 10=Butuh Penggantian Segera,11=Ticket di cancel");
            $table->integer('job_status')->nullable()->comment("0=Open, 1=Closed, 2=Cancel");
            $table->integer('stock_input')->nullable()->comment("0=No, 1=Yes");
            $table->text('notes')->nullable();
            $table->string('resi_image')->nullable();
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
        Schema::dropIfExists('warehouse_job_orders');
    }
}
