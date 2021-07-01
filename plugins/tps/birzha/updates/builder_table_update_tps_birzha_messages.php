<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaMessages extends Migration
{
    public function up()
    {
        Schema::rename('tps_birzha_message', 'tps_birzha_messages');
    }
    
    public function down()
    {
        Schema::rename('tps_birzha_messages', 'tps_birzha_message');
    }
}
