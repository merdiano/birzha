<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaOffers extends Migration
{
    public function up()
    {
        Schema::rename('tps_birzha_offer', 'tps_birzha_offers');
    }
    
    public function down()
    {
        Schema::rename('tps_birzha_offers', 'tps_birzha_offer');
    }
}
