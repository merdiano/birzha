<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaTransactions2 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_transactions', function($table)
        {
            $table->text('description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_transactions', function($table)
        {
            $table->dropColumn('description');
        });
    }
}
