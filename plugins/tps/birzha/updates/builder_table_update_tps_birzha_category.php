<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaCategory extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_category', function($table)
        {
            $table->boolean('status')->default(1);
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_category', function($table)
        {
            $table->dropColumn('status');
        });
    }
}
