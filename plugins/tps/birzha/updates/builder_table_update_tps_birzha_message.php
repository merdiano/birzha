<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaMessage extends Migration
{
    public function up()
    {
        Schema::rename('tps_birzha_', 'tps_birzha_message');
    }
    
    public function down()
    {
        Schema::rename('tps_birzha_message', 'tps_birzha_');
    }
}
