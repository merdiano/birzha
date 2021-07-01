<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProduct10 extends Migration
{
    public function up()
    {
        Schema::rename('tps_birzha_countries', 'tps_birzha_product');
    }
    
    public function down()
    {
        Schema::rename('tps_birzha_product', 'tps_birzha_countries');
    }
}
