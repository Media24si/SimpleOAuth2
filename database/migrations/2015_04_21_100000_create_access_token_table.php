<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessTokenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oauth_accessToken', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('client_id');
			$table->integer('user_id')->nullable()->unsigned();

			$table->string('token')->index();
			$table->integer('expires_at')->unsigned();
			$table->string('scope')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('oauth_accessToken');
	}

}


