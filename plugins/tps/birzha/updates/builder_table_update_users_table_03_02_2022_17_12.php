<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateUsers extends Migration
{
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->renameColumn('verified', 'phone_verified');
            $table->boolean('email_verified')->default(false);
        });
    }
    
    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->renameColumn('phone_verified', 'verified');
            $table->dropColumn('email_verified');
        });
    }
}
