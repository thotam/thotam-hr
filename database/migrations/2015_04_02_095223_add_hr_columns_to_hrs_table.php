<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHrColumnsToHrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrs', function (Blueprint $table) {
            $table->string('hr_key', 10)->nullable()->default(null)->after('password');
            $table->foreign('hr_key')->references('key')->on('user_infos')->onDelete('SET NULL')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrs', function (Blueprint $table) {
            $table->dropForeign(['hr_key']);

            $table->dropColumn('hr_key');
        });
    }
}
