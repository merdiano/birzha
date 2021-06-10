<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaPayment extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_payment', function($table)
        {
            $table->string('status');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_payment', function($table)
        {
            $table->dropColumn('status');
        });
    }
}
