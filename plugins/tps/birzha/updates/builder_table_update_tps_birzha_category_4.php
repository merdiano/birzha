<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaCategory4 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_category', function($table)
        {
            $table->integer('sort_order')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_category', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
