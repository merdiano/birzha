<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaOffer3 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_offer', function($table)
        {
            $table->text('status_note')->nullable();
            $table->integer('payment_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_offer', function($table)
        {
            $table->dropColumn('status_note');
            $table->dropColumn('payment_id');
        });
    }
}
