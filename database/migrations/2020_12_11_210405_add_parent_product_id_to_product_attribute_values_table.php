<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentProductIdToProductAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->foreignId('parent_product_id')->after('id')->nullable();

            $table->foreign('parent_product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->dropForeign(['parent_product_id']);

            $table->dropColumn('parent_product_id');
        });
    }
}










// h: DOKUMENTASI

// kita menambahkan parent_product_id pada tb product_attribute_values
// agar bisa berelasi langsung dengan parent product yang ada di tb product
