<?php

require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Permission;

$p1 = Permission::where('name', 'manage-female-shift')->first();
if ($p1) {
    $p1->group_key = 'Global';
    $p1->save();
}

$p2 = Permission::where('name', 'manage-male-shift')->first();
if ($p2) {
    $p2->group_key = 'Global';
    $p2->save();
}

echo "Permissions updated successfully.";
