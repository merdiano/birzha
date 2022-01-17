<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaExchangeRequests3 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_exchange_requests', function($table)
        {
            $table->string('currency');
            $table->decimal('total_price', 10, 2);
            $table->decimal('converted_to_tmt', 10, 2);
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_exchange_requests', function($table)
        {
            $table->dropColumn('currency');
            $table->dropColumn('total_price');
            $table->dropColumn('converted_to_tmt');
        });
    }
}
