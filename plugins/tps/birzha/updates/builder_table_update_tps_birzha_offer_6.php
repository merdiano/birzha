<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaOffer6 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_offer', function($table)
        {
            $table->dateTime('ends_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_offer', function($table)
        {
            $table->dropColumn('ends_at');
        });
    }
}
