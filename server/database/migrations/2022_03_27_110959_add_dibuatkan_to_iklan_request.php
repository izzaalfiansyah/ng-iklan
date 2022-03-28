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
        Schema::table('iklan_request', function (Blueprint $table) {
            $table->enum('dibuatkan', ['0', '1'])->comment('0: tidak, 1: ya')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('iklan_request', function (Blueprint $table) {
            $table->dropColumn('dibuatkan');
        });
    }
};
