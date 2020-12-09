<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdAndTypeToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('parent_id')->after('id')->nullable();
            $table->string('type')->after('sku');

            $table->foreign('parent_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            
            $table->dropColumn('parent_id');
            $table->dropColumn('type');
        });
    }
}










// h: DOKUMENTASI

// parent_id akan berelasi dengan tb products itu sendiri
