<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaMessages2 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_messages', function($table)
        {
            $table->renameColumn('chat_id', 'chatroom_id');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_messages', function($table)
        {
            $table->renameColumn('chatroom_id', 'chat_id');
        });
    }
}
