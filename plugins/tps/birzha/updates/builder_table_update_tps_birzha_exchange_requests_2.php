<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaExchangeRequests2 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_exchange_requests', function($table)
        {
            $table->string('status')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_exchange_requests', function($table)
        {
            $table->dropColumn('status');
        });
    }
}
