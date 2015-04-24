<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefreshTokenTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_refreshToken', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->integer('user_id')->nullable()->unsigned();

            $table->string('token');
            $table->integer('expires_at')->unsigned();
            $table->string('scope')->nullable();

			$table->index('token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oauth_refreshToken');
    }

}


