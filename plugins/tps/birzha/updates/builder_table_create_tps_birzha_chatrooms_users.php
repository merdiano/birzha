<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaChatroomsUsers extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_chatrooms_users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('id');
            $table->integer('user_id');
            $table->integer('chatroom_id');
            $table->primary(['id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_chatrooms_users');
    }
}
