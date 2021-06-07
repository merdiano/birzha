<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaProductCategories extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_product_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('product_id');
            $table->integer('category_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_product_categories');
    }
}
