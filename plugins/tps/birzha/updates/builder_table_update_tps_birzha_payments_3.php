<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaPayments3 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_payments', function($table)
        {
            $table->string('order_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_payments', function($table)
        {
            $table->dropColumn('order_id');
        });
    }
}
