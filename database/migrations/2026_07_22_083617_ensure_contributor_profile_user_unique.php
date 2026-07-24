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
        /*
        |--------------------------------------------------------------------------
        | Pastikan tabel contributor_profiles tersedia
        |--------------------------------------------------------------------------
        |
        | Migration ini bersifat korektif.
        | Kalau tabel belum ada, tidak ada yang perlu dilakukan.
        |
        */

        if (! Schema::hasTable('contributor_profiles')) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Cek apakah user_id sudah memiliki UNIQUE index
        |--------------------------------------------------------------------------
        |
        | Jangan gunakan:
        |
        | SHOW INDEX FROM contributor_profiles
        |
        | karena itu SQL spesifik MySQL dan akan gagal pada SQLite
        | yang digunakan saat automated testing.
        |
        | Schema::hasIndex() bersifat lintas database.
        |
        */

        if (
            Schema::hasIndex(
                'contributor_profiles',
                ['user_id'],
                'unique'
            )
        ) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Tambahkan UNIQUE constraint
        |--------------------------------------------------------------------------
        |
        | Satu user hanya boleh mempunyai satu contributor profile.
        |
        */

        Schema::table(
            'contributor_profiles',
            function (Blueprint $table): void {
                $table->unique(
                    'user_id',
                    'contributor_profile_user_unique'
                );
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('contributor_profiles')) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Hanya hapus index yang dibuat migration ini
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasIndex(
                'contributor_profiles',
                'contributor_profile_user_unique'
            )
        ) {
            Schema::table(
                'contributor_profiles',
                function (Blueprint $table): void {
                    $table->dropUnique(
                        'contributor_profile_user_unique'
                    );
                }
            );
        }
    }
};
