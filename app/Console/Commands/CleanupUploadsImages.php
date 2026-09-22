<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupUploadsImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uploads:cleanup-temp 
                            {--dry-run : Simula la ejecución mostrando los archivos que se borrarían sin eliminarlos}
                            {--force : Omite la confirmación interactiva para entornos automatizados}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina imágenes PNG temporales cuyo nombre consiste exactamente en 5 caracteres alfanuméricos en storage/app/public/uploads';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $isForce = (bool) $this->option('force');
        $directory = 'uploads';

        $disk = Storage::disk('public');

        if (!$disk->exists($directory)) {
            $this->warn("El directorio '{$directory}' no existe en el disco public.");
            return Command::SUCCESS;
        }

        $this->info("Buscando archivos en '{$directory}/'...");

        // Obtenemos todos los archivos del directorio uploads
        $allFiles = $disk->files($directory);

        // Filtramos solo archivos con nombre de 5 caracteres alfanuméricos y extensión .png (ej. 4ch1I.png)
        $matchingFiles = array_values(array_filter($allFiles, function ($filePath) {
            $filename = basename($filePath);
            return (bool) preg_match('/^[a-zA-Z0-9]{5}\.png$/i', $filename);
        }));

        $totalMatches = count($matchingFiles);

        if ($totalMatches === 0) {
            $this->info("No se encontraron archivos PNG de 5 caracteres alfanuméricos para eliminar.");
            return Command::SUCCESS;
        }

        $this->line("<comment>Archivos encontrados:</comment> {$totalMatches}");

        if ($isDryRun) {
            $this->warn("--- MODO SIMULACIÓN (--dry-run) ACTIVADO: No se borrará ningún archivo ---");
            $sample = array_slice($matchingFiles, 0, 50);
            foreach ($sample as $file) {
                $this->line("  [SIMULADO] " . $file);
            }

            if ($totalMatches > 50) {
                $this->line("  ... y " . ($totalMatches - 50) . " archivos más.");
            }

            $this->info("Simulación completada. Total de archivos candidatos a borrar: {$totalMatches}");
            return Command::SUCCESS;
        }

        // Si no se pasó --force y estamos en modo interactivo, pedir confirmación
        if (!$isForce) {
            if (!$this->confirm("¿Estás seguro de que deseas eliminar permanentemente {$totalMatches} archivo(s)?", false)) {
                $this->info("Operación cancelada por el usuario.");
                return Command::SUCCESS;
            }
        }

        $this->info("Eliminando archivos...");
        $bar = $this->output->createProgressBar($totalMatches);
        $bar->start();

        $deletedCount = 0;
        $failedCount = 0;

        foreach ($matchingFiles as $file) {
            if ($disk->delete($file)) {
                $deletedCount++;
            } else {
                $failedCount++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✔ Proceso finalizado.");
        $this->line("  - Archivos eliminados exitosamente: <info>{$deletedCount}</info>");

        if ($failedCount > 0) {
            $this->error("  - Archivos que no se pudieron eliminar: {$failedCount}");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
