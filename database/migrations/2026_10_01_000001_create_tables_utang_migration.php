<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kabupatens', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 64);
            $table->timestamps();
        });

        Schema::create('jenis_pajak', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 32)->unique();
            $table->string('nama', 64);
            $table->timestamps();
        });

        Schema::create('transaksi_utang', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun_anggaran');
            $table->foreignId('kabupaten_id')->constrained('kabupatens');
            $table->foreignId('jenis_pajak_id')->constrained('jenis_pajak');
            $table->unsignedTinyInteger('triwulan'); // 1-4
            $table->unsignedBigInteger('utang')->default(0);
            $table->unsignedBigInteger('pembayaran')->default(0);
            $table->unsignedBigInteger('sisa')->default(0);
            $table->timestamps();
            $table->unique(['tahun_anggaran', 'kabupaten_id', 'jenis_pajak_id', 'triwulan'], 'unique_transaksi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_utang');
        Schema::dropIfExists('jenis_pajak');
        Schema::dropIfExists('kabupatens');
    }
};
