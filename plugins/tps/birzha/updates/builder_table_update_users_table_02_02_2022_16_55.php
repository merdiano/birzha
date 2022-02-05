<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateUsers extends Migration
{
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->boolean('verified')->default(false);
        });
    }
    
    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn('verified');
        });
    }
}
