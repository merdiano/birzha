<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaOffer extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_offer', function($table)
        {
            $table->integer('payment_term_id')->nullable();
            $table->integer('deliver_term_id')->nullable();
            $table->boolean('packaging')->nullable();
            $table->string('place')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_offer', function($table)
        {
            $table->dropColumn('payment_term_id');
            $table->dropColumn('deliver_term_id');
            $table->dropColumn('packaging');
            $table->dropColumn('place');
        });
    }
}
