<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateUsers extends Migration
{
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('email_activation_code', 191)->nullable();
            $table->string('phone_activation_code', 191)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn('email_activation_code');
            $table->dropColumn('phone_activation_code');
        });
    }
}
