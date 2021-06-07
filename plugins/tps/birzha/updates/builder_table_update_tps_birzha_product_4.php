<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProduct4 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_product', function($table)
        {
            $table->text('status_desc')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_product', function($table)
        {
            $table->dropColumn('status_desc');
        });
    }
}
