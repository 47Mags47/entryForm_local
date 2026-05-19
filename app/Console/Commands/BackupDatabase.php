<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Создаёт бэкап базы данных и отправляет на удалённый сервер.';

    public function handle()
    {
        $backupDir = config('backup.backup_dir');

        $connection = config('database.default');
        $db = config("database.connections.$connection");

        $dbHost = $db['host'];
        $dbPort = $db['port'];
        $dbName = $db['database'];
        $dbUser = $db['username'];
        $dbPass = $db['password'];

        $rsync = config('backup.rsync');
        $rsyncUser = $rsync['user'];
        $rsyncPass = $rsync['password'];
        $rsyncHost = $rsync['host'];
        $rsyncPath = $rsync['path'];

        $this->info("Удаляем старые бэкапы...");

        $files = glob("$backupDir/*.sql");
        if (count($files) > 30) {
            usort($files, function ($a, $b) {
                return filemtime($a) <=> filemtime($b);
            });

            $toDelete = array_slice($files, 0, count($files) - 30);
            foreach ($toDelete as $file) {
                unlink($file);
                $this->line("Удалён старый файл: " . basename($file));
            }
        }

        $this->info("Очистка завершена");

        $date = date('Y-m-d_H-i-s');
        $filename = "$backupDir/db_{$date}.sql";

        $this->info("Создаём дамп базы...");
        $dump = "mysqldump -h $dbHost -P $dbPort -u $dbUser -p'$dbPass' $dbName > $filename";
        exec($dump, $output, $code);

        if ($code !== 0) {
            $this->error("Ошибка при создании дампа!");
            return 1;
        }

        $this->info("Дамп создан: $filename");

        if ($rsyncUser && $rsyncHost && $rsyncPath) {
            $this->info("Отправляем файлы бэкапов на сервер через rsync...");

            $rsyncCommand = "sshpass -p \"$rsyncPass\" rsync -at --delete --progress "
                . getcwd() . '/' . " $rsyncUser@$rsyncHost:$rsyncPath";

            $this->info("Процесс синхронизации...");
            exec($rsyncCommand . " 2>&1", $rsyncOutput, $rsyncCode);

            foreach ($rsyncOutput as $line) {
                $this->line($line);
            }

            if ($rsyncCode !== 0) {
                $this->error("Ошибка при передаче rsync!");
                return 1;
            }

            $this->info("Файлы успешно синхронизированы с удалённым сервером.");
        } else {
            $this->warn("Данные rsync не заданы — файл не отправлен.");
        }

        $this->info("Копирование завершено успешно.");
        return 0;
    }
}
