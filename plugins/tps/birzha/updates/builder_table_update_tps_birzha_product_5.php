<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProduct5 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_product', function($table)
        {
            $table->renameColumn('status_desc', 'status_note');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_product', function($table)
        {
            $table->renameColumn('status_note', 'status_desc');
        });
    }
}
