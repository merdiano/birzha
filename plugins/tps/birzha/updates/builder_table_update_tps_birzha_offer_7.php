<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaOffer7 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_offer', function($table)
        {
            $table->renameColumn('deliver_term_id', 'delivery_term_id');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_offer', function($table)
        {
            $table->renameColumn('delivery_term_id', 'deliver_term_id');
        });
    }
}
