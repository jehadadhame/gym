<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Permission;

class CreateShiftManagerPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::create([
            'name'         => 'manage-female-shift',
            'display_name' => 'Manage Female Shift',
            'description'  => 'Allows a manager to access and modify female shift data only.',
            'group_key'    => 'Global',
        ]);

        Permission::create([
            'name'         => 'manage-male-shift',
            'display_name' => 'Manage Male Shift',
            'description'  => 'Allows a manager to access and modify male shift data only.',
            'group_key'    => 'Global',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'manage-female-shift')->delete();
        Permission::where('name', 'manage-male-shift')->delete();
    }
}
