<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProducts3 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_products', function($table)
        {
            $table->integer('quantity');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_products', function($table)
        {
            $table->dropColumn('quantity');
        });
    }
}
