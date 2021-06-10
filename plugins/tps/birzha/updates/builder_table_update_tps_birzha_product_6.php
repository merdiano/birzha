<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProduct6 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_product', function($table)
        {
            $table->string('mark')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_product', function($table)
        {
            $table->dropColumn('mark');
        });
    }
}
