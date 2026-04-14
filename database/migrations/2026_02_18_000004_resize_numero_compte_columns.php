<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE bgfi_comptes MODIFY numero_compte VARCHAR(20) NOT NULL');
        DB::statement('ALTER TABLE bgfi_comptes_tampon MODIFY numero_compte VARCHAR(20) NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE bgfi_comptes MODIFY numero_compte VARCHAR(10) NOT NULL');
        DB::statement('ALTER TABLE bgfi_comptes_tampon MODIFY numero_compte VARCHAR(10) NOT NULL');
    }
};
