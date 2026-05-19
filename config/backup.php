<?php

return [
    'backup_dir' => storage_path('app/backups'),

     'rsync' => [
        'user' => env('RSYNC_USER', 'backupuser'),
        'password' => env('RSYNC_PASSWORD', ''), // используется через sshpass
        'host' => env('RSYNC_HOST', 'example.com'),
        'path' => env('RSYNC_PATH', '/remote/backups'),
    ],

    'retention_days' => env('BACKUP_RETENTION_DAYS', 30),
];
