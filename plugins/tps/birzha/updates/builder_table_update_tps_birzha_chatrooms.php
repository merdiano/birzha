<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaChatrooms extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_chatrooms', function($table)
        {
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->dropColumn('user_id');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_chatrooms', function($table)
        {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->integer('user_id');
        });
    }
}
