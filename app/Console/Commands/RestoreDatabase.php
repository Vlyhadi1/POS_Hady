<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RestoreDatabase extends Command
{
    protected $signature = 'db:restore {file : Path file .sql} {--force : Lewati konfirmasi}';
    protected $description = 'Memulihkan database MySQL dari file backup SQL';

    public function handle(): int
    {
        if (config('database.default') !== 'mysql') {
            $this->error('Restore ini hanya mendukung koneksi MySQL.');
            return self::FAILURE;
        }

        $file = $this->argument('file');
        if (!is_file($file)) {
            $this->error('File backup tidak ditemukan.');
            return self::FAILURE;
        }
        if (!$this->option('force') && !$this->confirm('Restore akan menimpa data saat ini. Lanjutkan?')) {
            return self::SUCCESS;
        }

        $connection = config('database.connections.mysql');
        $command = sprintf(
            'mysql --host=%s --port=%s --user=%s %s %s < %s',
            escapeshellarg($connection['host']), escapeshellarg($connection['port']),
            escapeshellarg($connection['username']),
            $connection['password'] !== '' ? '--password=' . escapeshellarg($connection['password']) : '',
            escapeshellarg($connection['database']), escapeshellarg($file)
        );
        system($command, $exitCode);
        if ($exitCode !== 0) {
            $this->error('Restore gagal. Pastikan mysql tersedia di PATH.');
            return self::FAILURE;
        }
        $this->info('Restore database berhasil.');
        return self::SUCCESS;
    }
}