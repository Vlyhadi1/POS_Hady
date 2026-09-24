<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Membuat backup database MySQL ke storage/app/backups';

    public function handle(): int
    {
        if (config('database.default') !== 'mysql') {
            $this->error('Backup ini hanya mendukung koneksi MySQL.');
            return self::FAILURE;
        }

        $connection = config('database.connections.mysql');
        $directory = storage_path('app/backups');
        File::ensureDirectoryExists($directory);
        $filename = $directory . DIRECTORY_SEPARATOR . 'backup-' . now()->format('Y-m-d_His') . '.sql';
        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s %s %s',
            escapeshellarg($connection['host']),
            escapeshellarg($connection['port']),
            escapeshellarg($connection['username']),
            $connection['password'] !== '' ? '--password=' . escapeshellarg($connection['password']) : '',
            escapeshellarg($connection['database'])
        );

        $exitCode = 0;
        system($command . ' > ' . escapeshellarg($filename), $exitCode);
        if ($exitCode !== 0) {
            @unlink($filename);
            $this->error('Backup gagal. Pastikan mysqldump tersedia di PATH.');
            return self::FAILURE;
        }

        $this->info('Backup berhasil: ' . $filename);
        return self::SUCCESS;
    }
}
