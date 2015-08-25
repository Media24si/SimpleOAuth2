<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthCodeTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_authCode', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('client_id');
            $table->integer('user_id')->nullable()->unsigned();

            $table->string('token')->index();
            $table->integer('expires_at')->unsigned();
            $table->string('scope')->nullable();
            $table->string('redirect_uri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oauth_authCode');
    }

}
