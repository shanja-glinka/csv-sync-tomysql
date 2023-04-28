<?
return  [
    'csv' => [
        'tableName' => 'Products',
        // 'path' => 'doc/example.csv',
        'path' => 'https://docs.google.com/spreadsheets/u/1/d/11baEe84ByoAuOuptOopxTh653kjt8hf45ZC9dtx-t5A/export?format=csv&id=11baEe84ByoAuOuptOopxTh653kjt8hf45ZC9dtx-t5A&gid=0',
        'croninterval' => 120
    ],
    'connection' => [
        'host' => '127.0.0.1',
        'database' => 'csv-sync-db',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ]
];
