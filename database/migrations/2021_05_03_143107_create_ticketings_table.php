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
            $table->string('uuid_unit')->nullable();
            $table->string('ticket_number')->nullable();
            $table->integer('urgent_status')->nullable()->comment('0=non urgent, 1=urgent');
            $table->integer('ticket_status')->nullable()->comment('1=Diproses ke bagian repair, 2=Diproses ke bagian gudang, 3=Selesai', '4=Cancel');
            $table->integer('job_status')->nullable()->comment("1=Dalam penanganan oleh teknisi, 2=Telah diperbaiki oleh teknisi, 3=Tidak dapat diperbaiki teknisi,4=Butuh klaim garansi, 5=Butuh penggantian barang, 6=Dalam perbaikan oleh vendor, 7=Menunggu penggantian dari vendor, 8=Telah di kirim ke customer, 9=Ticket di cancel");
            $table->text('note')->nullable();
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
