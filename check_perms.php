<?php

require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Permission;

$perms = Permission::whereIn('name', ['manage-female-shift', 'manage-male-shift'])->get();

if ($perms->isEmpty()) {
    echo "Permissions are NOT in the database!\n";
    // Let's insert them using basic Eloquent save instead of firstOrCreate
    $p1 = new Permission();
    $p1->name = 'manage-female-shift';
    $p1->display_name = 'Manage Female Shift';
    $p1->description = 'Allows a manager to access and modify female shift data only.';
    $p1->group_key = 'Global';
    $p1->save();
    
    $p2 = new Permission();
    $p2->name = 'manage-male-shift';
    $p2->display_name = 'Manage Male Shift';
    $p2->description = 'Allows a manager to access and modify male shift data only.';
    $p2->group_key = 'Global';
    $p2->save();
    
    echo "Inserted permissions manually.\n";
} else {
    echo "Permissions found in the database:\n";
    foreach ($perms as $p) {
        echo "- " . $p->name . " (group: " . $p->group_key . ")\n";
    }
}
