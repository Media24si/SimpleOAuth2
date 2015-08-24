<?php

return [

	/**
	 * Used for laravel Auth::attempt (see: http://laravel.com/docs/5.0/authentication#authenticating-users)
	 */
	'user' => [

		'username_field' => 'email',
		'password_field' => 'password',
		'conditions' => []
	],

	/**
	 * Custom grant types
	 * Example:
	 * 	'http://api.com/my_grant_type' => 'App\GrantTypes\MyGrantType'
	 */
	'grant_extensions' => [

	],

	/**
	 * For all possible configurable options see: https://github.com/FriendsOfSymfony/oauth2-php/blob/master/lib/OAuth2.php#L124
	 * Example:
	 * \OAuth2\OAuth2::CONFIG_ACCESS_LIFETIME => 60
	 */
	'config' => [
	],

];
