<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter sexe aux deux tables
        DB::statement("ALTER TABLE bgfi_comptes ADD COLUMN sexe VARCHAR(1) NULL DEFAULT NULL AFTER devise");
        DB::statement("ALTER TABLE bgfi_comptes_tampon ADD COLUMN sexe VARCHAR(1) NULL DEFAULT NULL AFTER devise");

        // Mettre a jour la procedure stockee
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_maj_bgfi_comptes');

        DB::unprepared('
            CREATE PROCEDURE sp_maj_bgfi_comptes()
            BEGIN
                INSERT INTO bgfi_comptes (numero_compte, nom_complet, telephone, solde, devise, sexe, created_at, updated_at)
                SELECT numero_compte, nom_complet, telephone, solde, devise, sexe, NOW(), NOW()
                FROM bgfi_comptes_tampon
                ON DUPLICATE KEY UPDATE
                    nom_complet = VALUES(nom_complet),
                    telephone = VALUES(telephone),
                    solde = VALUES(solde),
                    devise = VALUES(devise),
                    sexe = VALUES(sexe),
                    updated_at = NOW();

                TRUNCATE TABLE bgfi_comptes_tampon;
            END
        ');
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE bgfi_comptes DROP COLUMN sexe");
        DB::statement("ALTER TABLE bgfi_comptes_tampon DROP COLUMN sexe");

        // Restaurer l'ancienne procedure sans sexe
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_maj_bgfi_comptes');
        DB::unprepared('
            CREATE PROCEDURE sp_maj_bgfi_comptes()
            BEGIN
                INSERT INTO bgfi_comptes (numero_compte, nom_complet, telephone, solde, devise, created_at, updated_at)
                SELECT numero_compte, nom_complet, telephone, solde, devise, NOW(), NOW()
                FROM bgfi_comptes_tampon
                ON DUPLICATE KEY UPDATE
                    nom_complet = VALUES(nom_complet),
                    telephone = VALUES(telephone),
                    solde = VALUES(solde),
                    devise = VALUES(devise),
                    updated_at = NOW();

                TRUNCATE TABLE bgfi_comptes_tampon;
            END
        ');
    }
};
