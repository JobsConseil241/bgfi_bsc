<?php

namespace App\Console\Commands;

use App\Models\BgfiCompteTampon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ImportBgfiComptes extends Command
{
    protected $signature = 'bgfi:import';

    protected $description = 'Importe les comptes BGFI depuis les fichiers CSV dans database/bgfi_data/';

    private function importPath(): string
    {
        return database_path('bgfi_data');
    }

    private function archivePath(): string
    {
        return database_path('bgfi_data/archives');
    }

    public function handle(): int
    {
        $this->info('Debut de l\'import BGFI...');

        $importDir = $this->importPath();

        if (!File::isDirectory($importDir)) {
            $this->error("Dossier introuvable : {$importDir}");
            return self::FAILURE;
        }

        // Chercher les fichiers .csv et .txt
        $files = array_merge(
            File::glob($importDir . '/*.csv'),
            File::glob($importDir . '/*.txt')
        );

        if (empty($files)) {
            $this->warn('Aucun fichier .csv ou .txt trouve dans database/bgfi_data/');
            return self::SUCCESS;
        }

        foreach ($files as $file) {
            $this->processFile($file);
        }

        return self::SUCCESS;
    }

    /**
     * Detecte le separateur du fichier en testant la premiere ligne de donnees
     */
    private function detectSeparator(string $filePath): string
    {
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            return ',';
        }

        // Lire les 2 premieres lignes (header + premiere donnee)
        $firstLine = fgets($handle);
        $secondLine = fgets($handle);
        fclose($handle);

        $lineToTest = $secondLine ?: $firstLine;

        if ($lineToTest === false) {
            return ',';
        }

        // Tester chaque separateur et compter les champs obtenus
        $separators = [',' => 0, "\t" => 0, ';' => 0, '|' => 0];

        foreach ($separators as $sep => &$count) {
            $fields = str_getcsv($lineToTest, $sep, '"');
            $count = count($fields);
        }

        // Le separateur qui donne le plus de champs (et au moins 5) est le bon
        arsort($separators);
        $bestSep = array_key_first($separators);

        return $separators[$bestSep] >= 5 ? $bestSep : ',';
    }

    /**
     * Detecte l'encodage du fichier et le convertit en UTF-8 si necessaire
     */
    private function convertToUtf8(string $filePath): void
    {
        $content = file_get_contents($filePath);

        if ($content === false) {
            return;
        }

        // Supprimer le BOM UTF-8 si present
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        // Detecter l'encodage
        $encoding = mb_detect_encoding($content, ['UTF-8', 'Windows-1252', 'ISO-8859-1', 'ISO-8859-15'], true);

        if ($encoding && $encoding !== 'UTF-8') {
            $this->info("Encodage detecte : {$encoding} -> conversion en UTF-8");
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
            file_put_contents($filePath, $content);
        } else {
            $this->info("Encodage : UTF-8");
        }
    }

    private function processFile(string $filePath): void
    {
        $filename = basename($filePath);
        $this->info("Traitement de : {$filename}");

        try {
            // Auto-detection et conversion de l'encodage vers UTF-8
            $this->convertToUtf8($filePath);

            // Auto-detection du separateur
            $separator = $this->detectSeparator($filePath);
            $sepName = match ($separator) {
                ',' => 'virgule',
                "\t" => 'tabulation',
                ';' => 'point-virgule',
                '|' => 'pipe',
                default => $separator,
            };
            $this->info("Separateur detecte : {$sepName}");

            $handle = fopen($filePath, 'r');

            if ($handle === false) {
                $this->error("Impossible d'ouvrir {$filename}");
                return;
            }

            // Lire la ligne d'en-tete
            $header = fgetcsv($handle, 0, $separator, '"');

            if ($header === false) {
                $this->error("Fichier {$filename} vide.");
                fclose($handle);
                return;
            }

            // Normaliser les noms de colonnes (minuscules, sans espaces/quotes)
            $header = array_map(function ($col) {
                return strtolower(trim($col, " \t\n\r\0\x0B\""));
            }, $header);

            $this->info("En-tete : " . implode(' | ', $header));

            // Mapper les colonnes par nom
            $columnMap = array_flip($header);

            $requiredColumns = ['numero_compte', 'nom_complet', 'telephone', 'solde'];
            $missingColumns = array_diff($requiredColumns, array_keys($columnMap));

            if (!empty($missingColumns)) {
                $this->error("Colonnes manquantes dans {$filename} : " . implode(', ', $missingColumns));
                fclose($handle);
                return;
            }

            // Vider la table tampon
            BgfiCompteTampon::truncate();

            $imported = 0;
            $errors = 0;
            $lineNumber = 1;

            while (($fields = fgetcsv($handle, 0, $separator, '"')) !== false) {
                $lineNumber++;

                // Ignorer les lignes vides
                if (count($fields) === 1 && trim($fields[0] ?? '') === '') {
                    continue;
                }

                if (count($fields) < count($requiredColumns)) {
                    $rawLine = implode($separator, $fields);
                    $this->warn("Ligne {$lineNumber} ignoree (" . count($fields) . " champs) : " . mb_substr($rawLine, 0, 120));
                    Log::warning("Import BGFI - ligne {$lineNumber} ignoree", ['contenu' => $rawLine]);
                    $errors++;
                    continue;
                }

                try {
                    $get = function (string $col) use ($fields, $columnMap) {
                        if (!isset($columnMap[$col])) return null;
                        return trim($fields[$columnMap[$col]] ?? '', " \t\n\r\0\x0B\"");
                    };

                    $numeroCompte = $get('numero_compte');
                    $nomComplet = $get('nom_complet');
                    $telephone = $get('telephone');
                    $solde = (float) $get('solde');
                    $devise = $get('devise') ?: 'XAF';
                    $sexe = $get('sexe') ? strtoupper($get('sexe')) : null;

                    // Valider sexe : M, F ou null
                    if ($sexe && !in_array($sexe, ['M', 'F'])) {
                        $sexe = null;
                    }

                    if (empty($numeroCompte)) {
                        $this->warn("Ligne {$lineNumber} ignoree : numero_compte vide");
                        $errors++;
                        continue;
                    }

                    BgfiCompteTampon::create([
                        'numero_compte' => $numeroCompte,
                        'nom_complet'   => $nomComplet,
                        'telephone'     => $telephone,
                        'solde'         => $solde,
                        'devise'        => $devise,
                        'sexe'          => $sexe,
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $this->error("Ligne {$lineNumber} en erreur : {$e->getMessage()}");
                    $errors++;
                }
            }

            fclose($handle);

            $this->info("Tampon remplie : {$imported} comptes importes, {$errors} erreurs.");

            // Appeler la procedure stockee
            DB::unprepared('CALL sp_maj_bgfi_comptes()');
            $this->info('Procedure stockee executee : table principale mise a jour.');

            // Archiver le fichier
            $archiveDir = $this->archivePath();
            File::ensureDirectoryExists($archiveDir);
            $archiveName = $archiveDir . '/' . date('Ymd_His') . '_' . $filename;
            File::move($filePath, $archiveName);
            $this->info("Fichier archive : {$archiveName}");

            Log::info('Import BGFI termine', [
                'fichier' => $filename,
                'importes' => $imported,
                'erreurs' => $errors,
            ]);

        } catch (\Exception $e) {
            $this->error("Erreur lors du traitement de {$filename} : {$e->getMessage()}");
            Log::error('Erreur import BGFI', [
                'fichier' => $filename,
                'erreur' => $e->getMessage(),
            ]);
        }
    }
}
