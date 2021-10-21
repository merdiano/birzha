<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProducts23 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_products', function($table)
        {
            $table->string('market_type')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_products', function($table)
        {
            $table->dropColumn('market_type');
        });
    }
}
