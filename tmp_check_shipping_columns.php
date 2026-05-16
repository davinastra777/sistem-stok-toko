<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$schema = $app->make('db')->getDoctrineSchemaManager();
$columns = $schema->listTableColumns('shipping_labels');
foreach ($columns as $name => $col) {
    echo $name . ': ' . $col->getType()->getName() . PHP_EOL;
}
