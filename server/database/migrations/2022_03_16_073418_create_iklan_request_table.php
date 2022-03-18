<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iklan_request', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('iklan_id')->unsigned();
            $table->string('link_video');
            $table->enum('status', ['1', '2', '0'])->comment('1 = setuju, 2 = tolak, 3 = menunggu')->default('0');
            $table->string('bukti_pembayaran')->nullable();
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
        Schema::dropIfExists('iklan_request');
    }
};
