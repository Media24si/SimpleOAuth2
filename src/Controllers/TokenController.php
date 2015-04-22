<?php namespace Media24si\SimpleOAuth2\Controllers;

use Media24si\SimpleOAuth2\OAuthStorage;
use Illuminate\Routing\Controller as BaseController;
use OAuth2\OAuth2;
use OAuth2\OAuth2ServerException;

class TokenController extends BaseController {
	//use DispatchesCommands, ValidatesRequests;

	public function token() {

		$oauth = new OAuth2(new OAuthStorage());

		try {
			$response = $oauth->grantAccessToken();
			$response->send();
		} catch (OAuth2ServerException $oauthError) {
			return $oauthError->getHttpResponse();
		}
	}

}