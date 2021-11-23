<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaContactformMessages2 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_contactform_messages', function($table)
        {
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_contactform_messages', function($table)
        {
            $table->dropColumn('deleted_at');
        });
    }
}
