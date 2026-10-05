<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom role pada tabel users dan pastikan
     * semua user eksisting memiliki role.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('operator')->after('password');
        });

        // Jadikan user pertama (id terkecil) sebagai admin agar tetap
        // ada admin setelah migrasi pada database yang sudah berisi user.
        $firstUser = DB::table('users')->orderBy('id')->value('id');
        if ($firstUser !== null) {
            DB::table('users')->where('id', $firstUser)->update(['role' => 'admin']);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
