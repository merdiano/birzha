<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaMessages extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_messages', function($table)
        {
            $table->integer('chat_id');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_messages', function($table)
        {
            $table->dropColumn('chat_id');
        });
    }
}
