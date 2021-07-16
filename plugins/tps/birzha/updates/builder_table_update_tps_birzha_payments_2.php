<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaPayments2 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_payments', function($table)
        {
            $table->text('bank_file')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_payments', function($table)
        {
            $table->dropColumn('bank_file');
        });
    }
}
