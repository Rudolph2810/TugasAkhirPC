<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_rkap_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->integer('no');
            $table->year('tahun');
            $table->string('kode_anggaran');
            $table->text('detail_rencana');
            $table->decimal('nilai_rkap', 15, 2);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
    }
};