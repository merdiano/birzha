<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaCategory3 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_category', function($table)
        {
            $table->renameColumn('title', 'name');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_category', function($table)
        {
            $table->renameColumn('name', 'title');
        });
    }
}
