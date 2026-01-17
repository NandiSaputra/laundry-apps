<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('transaksis', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('status_transaksi_histories', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status_transaksi_histories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
