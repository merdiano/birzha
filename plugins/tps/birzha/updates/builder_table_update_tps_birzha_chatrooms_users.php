<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaChatroomsUsers extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_chatrooms_users', function($table)
        {
            $table->increments('id')->change();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_chatrooms_users', function($table)
        {
            $table->integer('id')->change();
        });
    }
}
