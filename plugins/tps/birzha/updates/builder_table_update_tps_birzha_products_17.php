<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProducts17 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_products', function($table)
        {
            $table->decimal('payed_fee_for_publ', 10, 2)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_products', function($table)
        {
            $table->dropColumn('payed_fee_for_publ');
        });
    }
}
