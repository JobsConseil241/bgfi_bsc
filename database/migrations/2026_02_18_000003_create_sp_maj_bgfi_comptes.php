<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_maj_bgfi_comptes');

        DB::unprepared('
            CREATE PROCEDURE sp_maj_bgfi_comptes()
            BEGIN
                -- Upsert : INSERT ou UPDATE depuis la tampon vers la principale
                INSERT INTO bgfi_comptes (numero_compte, nom_complet, telephone, solde, devise, created_at, updated_at)
                SELECT numero_compte, nom_complet, telephone, solde, devise, NOW(), NOW()
                FROM bgfi_comptes_tampon
                ON DUPLICATE KEY UPDATE
                    nom_complet = VALUES(nom_complet),
                    telephone = VALUES(telephone),
                    solde = VALUES(solde),
                    devise = VALUES(devise),
                    updated_at = NOW();

                -- Vider la table tampon apres traitement
                TRUNCATE TABLE bgfi_comptes_tampon;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_maj_bgfi_comptes');
    }
};
