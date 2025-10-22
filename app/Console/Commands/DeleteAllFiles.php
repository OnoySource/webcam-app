<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteAllFiles extends Command
{
    protected $signature = 'files:delete-all';

    protected $description = 'Delete all files in public storage directory';

    public function handle()
    {
        $disk = Storage::disk('public');

        // Ambil daftar semua file di dalam direktori
        $files = $disk->allFiles();

        // Hapus semua file satu persatu
        foreach ($files as $file) {
            $disk->delete($file);
            $this->info("Deleted file: $file");
        }

        $this->info('All files deleted successfully!');
    }
}