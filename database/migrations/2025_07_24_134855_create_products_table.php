<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->integer('id', true)->comment('Unique Record Id for system');
            $table->string('product_code', 50)->unique("product_id")->comment('Unique product id for reference');
            $table->string('product_name', 50)->comment('name of the product');
            $table->text('product_details', 65535)->comment('product details');
            $table->integer('amount')->comment('amount to charge for the product');
            $table->boolean('status')->comment('0 for inactive , 1 for active');
            $table->timestamps();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('created_by', 'FK_products_mst_users_1')
                ->references('id')->on('mst_users')->onUpdate('RESTRICT')->onDelete('RESTRICT');

            $table->foreign('updated_by', 'FK_products_mst_users_2')
                ->references('id')->on('mst_users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}
