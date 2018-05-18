<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnIpSercureToTableOauthClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('migration')->table('oauth_clients', function ($table) {
            $table->dropColumn('ip_secure');
        });

        Schema::connection('migration')->table('oauth_clients', function (Blueprint $table) {
            $table->string('ip_secure')->nullable()->after('redirect');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
