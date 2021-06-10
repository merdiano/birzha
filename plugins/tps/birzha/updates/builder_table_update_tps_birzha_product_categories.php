<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProductCategories extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_product_categories', function($table)
        {
            $table->dropColumn('id');
            $table->primary(['product_id','category_id']);
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_product_categories', function($table)
        {
            $table->dropPrimary(['product_id','category_id']);
            $table->increments('id')->unsigned();
        });
    }
}
