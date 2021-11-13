<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaTransactions extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_transactions', function($table)
        {
            $table->integer('user_id');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_transactions', function($table)
        {
            $table->dropColumn('user_id');
        });
    }
}
