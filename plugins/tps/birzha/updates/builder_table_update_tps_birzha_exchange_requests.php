<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaExchangeRequests extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_exchange_requests', function($table)
        {
            $table->renameColumn('vendor_id', 'user_id');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_exchange_requests', function($table)
        {
            $table->renameColumn('user_id', 'vendor_id');
        });
    }
}
