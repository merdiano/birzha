<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaCategories extends Migration
{
    public function up()
    {
        Schema::rename('tps_birzha_category', 'tps_birzha_categories');
    }
    
    public function down()
    {
        Schema::rename('tps_birzha_categories', 'tps_birzha_category');
    }
}
