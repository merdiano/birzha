<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaPayments4 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_payments', function($table)
        {
            $table->decimal('amount', 10, 2)->change();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_payments', function($table)
        {
            $table->decimal('amount', 10, 0)->change();
        });
    }
}
