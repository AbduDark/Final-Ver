
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DatabaseBackup extends Command
{
    protected $signature = 'backup:database {--compress}';
    protected $description = 'Create a database backup';

    public function handle()
    {
        $filename = 'backup-' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
        $compress = $this->option('compress');
        
        if ($compress) {
            $filename .= '.gz';
        }

        $this->info('بدء عمل النسخ الاحتياطي...');

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database')
        );

        if ($compress) {
            $command .= ' | gzip';
        }

        $command .= ' > ' . storage_path('app/backups/' . $filename);

        // إنشاء مجلد النسخ الاحتياطي إذا لم يكن موجوداً
        if (!Storage::exists('backups')) {
            Storage::makeDirectory('backups');
        }

        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result === 0) {
            $this->info('تم إنشاء النسخة الاحتياطية بنجاح: ' . $filename);
            
            // حذف النسخ الاحتياطية القديمة (أكثر من 30 يوم)
            $this->cleanOldBackups();
        } else {
            $this->error('فشل في إنشاء النسخة الاحتياطية');
            return 1;
        }

        return 0;
    }

    private function cleanOldBackups()
    {
        $files = Storage::files('backups');
        $cutoff = Carbon::now()->subDays(30);

        foreach ($files as $file) {
            $fileTime = Storage::lastModified($file);
            if (Carbon::createFromTimestamp($fileTime)->lt($cutoff)) {
                Storage::delete($file);
                $this->info('تم حذف النسخة الاحتياطية القديمة: ' . basename($file));
            }
        }
    }
}
