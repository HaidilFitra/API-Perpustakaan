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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('buku_id');
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali');
            $table->enum('status',['pinjam','kembali']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('buku_id')->references('id')->on('book')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('peminjaman', function (Blueprint $table) {
        //     $table->dropForeign(['buku_id']);
        //     $table->dropForeign(['user_id']);
        // });
        Schema::dropIfExists('peminjaman');
    }
};
