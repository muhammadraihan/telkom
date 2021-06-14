<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticketings', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('uuid_pelanggan')->nullable();
            $table->string('ticket_number')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('ticket_status')->nullable()->comment('1=Diproses ke bagian repair, 2=Diproses ke bagian gudang, 3=Selesai', '4=Cancel');
            $table->integer('job_status')->nullable()->comment("1=Dalam penanganan oleh teknisi, 2=Telah diperbaiki oleh teknisi,3=Butuh klaim garansi,4= Butuh penggantian barang,5=Dalam perbaikan oleh vendor, 6=Menunggu penggantian dari vendor, 7=Telah di kirim ke customer, 8=Ticket di cancel");
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
        Schema::dropIfExists('ticketings');
    }
}
