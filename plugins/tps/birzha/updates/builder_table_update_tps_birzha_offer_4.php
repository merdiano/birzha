<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaOffer4 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_offer', function($table)
        {
            $table->smallInteger('name');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_offer', function($table)
        {
            $table->dropColumn('name');
        });
    }
}
