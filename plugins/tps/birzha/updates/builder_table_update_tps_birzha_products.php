<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProducts extends Migration
{
    public function up()
    {
        Schema::rename('tps_birzha_product', 'tps_birzha_products');
    }
    
    public function down()
    {
        Schema::rename('tps_birzha_products', 'tps_birzha_product');
    }
}
