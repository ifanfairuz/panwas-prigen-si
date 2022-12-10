<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteDocumentCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'document:deleteCache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete document cache like generated docx and pdf';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = Storage::files('surat_keluar');
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_BASENAME) === '.gitignore') continue;
            Storage::delete($file);
        }
        $dirs = Storage::directories('generated');
        foreach ($dirs as $dir) {
            Storage::deleteDirectory($dir);
        }
        return Command::SUCCESS;
    }
}
