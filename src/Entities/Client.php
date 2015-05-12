<?php  namespace Media24si\SimpleOAuth2\Entities;

use Illuminate\Database\Eloquent\Model;
use OAuth2\Model\IOAuth2Client;

class Client extends Model implements IOAuth2Client {

	protected $table = 'oauth_client';

	protected $casts = [
		'redirect_uris' => 'array',
		'allowed_grant_types' => 'array'
	];

	public function checkSecret($secret) {
		return $this->secret === $secret;
	}

	/**
	 * @return string
	 */
	public function getPublicId()
	{
		return $this->id;
	}

	/**
	 * @return array
	 */
	public function getRedirectUris()
	{
		return $this->redirect_uris;
	}
}