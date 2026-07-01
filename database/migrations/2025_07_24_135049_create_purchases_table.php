<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('member_id')->comment('links to unique record id of mst_members');
            $table->integer('product_id')->comment('links to unique record id of products');
            $table->integer('invoice_id')->index('FK_purchases_trn_invoice')->comment('links to unique record id of trn_invoice');
            $table->timestamps();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();

            $table->foreign('created_by', 'FK_purchases_mst_users_1')
                ->references('id')->on('mst_users')->onUpdate('RESTRICT')->onDelete('RESTRICT');

            $table->foreign('updated_by', 'FK_purchases_mst_users_2')
                ->references('id')->on('mst_users')->onUpdate('RESTRICT')->onDelete('RESTRICT');

            $table->foreign('member_id', 'FK_purchases_mst_member')
                ->references('id')->on('mst_members')->onUpdate('RESTRICT')->onDelete('RESTRICT');

            $table->foreign('product_id', 'FK_purchases_product')
                ->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('RESTRICT');

            $table->foreign('invoice_id', 'FK_purchases_mst_invoice')
                ->references('id')->on('trn_invoice')->onUpdate('RESTRICT')->onDelete('RESTRICT');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("purchases");
    }
}
