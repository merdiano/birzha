<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaMeasures extends Migration
{
    public function up()
    {
        Schema::rename('tps_birzha_measure', 'tps_birzha_measures');
    }
    
    public function down()
    {
        Schema::rename('tps_birzha_measures', 'tps_birzha_measure');
    }
}
