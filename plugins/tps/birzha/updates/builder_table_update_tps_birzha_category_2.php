<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaCategory2 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_category', function($table)
        {
            $table->string('slug');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_category', function($table)
        {
            $table->dropColumn('slug');
        });
    }
}
