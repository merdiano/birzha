<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProduct9 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_product', function($table)
        {
            $table->renameColumn('manufacture', 'manufacturer');
            $table->renameColumn('madein_id', 'country_id');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_product', function($table)
        {
            $table->renameColumn('manufacturer', 'manufacture');
            $table->renameColumn('country_id', 'madein_id');
        });
    }
}
