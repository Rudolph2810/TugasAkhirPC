<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_attachments', function (Blueprint $table) {
            // Pastikan kolom file_path ada dan tidak nullable
            if (!Schema::hasColumn('project_attachments', 'file_path')) {
                $table->string('file_path')->after('description');
            }
            
            // Tambahkan kolom original_filename jika belum ada
            if (!Schema::hasColumn('project_attachments', 'original_filename')) {
                $table->string('original_filename')->after('file_path');
            }
            
            // Tambahkan kolom uploaded_by jika belum ada
            if (!Schema::hasColumn('project_attachments', 'uploaded_by')) {
                $table->foreignId('uploaded_by')->nullable()->constrained('users');
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_attachments', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'original_filename', 'uploaded_by']);
        });
    }
};