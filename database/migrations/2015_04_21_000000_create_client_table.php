<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oauth_client', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->string('secret');
			$table->string('name');
			$table->json('redirect_uris');
			$table->json('allowed_grant_types');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('oauth_client');
	}

}


