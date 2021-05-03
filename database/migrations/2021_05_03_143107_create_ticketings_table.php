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
            $table->integer('ticket_status')->nullable()->comment('0= Diproses, 1=Selesai');
            $table->integer('job_status')->nullable()->comment("1=Butuh perbaikan dari vendor, 2=Menunggu perbaikan dari vendor
            3=Menunggu penggantian dari vendor, 4=Telah diperbaiki oleh teknisi, 5=Telah di kirim ke customer");
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
