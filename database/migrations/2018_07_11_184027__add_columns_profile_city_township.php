<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsProfileCityTownship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile', function (Blueprint $table) {
            $table->string('prefer_country', 100);
            $table->string('prefer_city', 100);
            $table->string('prefer_township', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile', function (Blueprint $table) {
            $table->dropColumn('prefer_country');
            $table->dropColumn('prefer_city');
            $table->dropColumn('prefer_township');
        });
    }
}
