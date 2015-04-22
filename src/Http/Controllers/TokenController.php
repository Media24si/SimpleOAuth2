<?php namespace Media24si\SimpleOAuth2\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use OAuth2\OAuth2;
use OAuth2\OAuth2ServerException;

class TokenController extends BaseController {

	public function token(OAuth2 $oauth) {
		try {
			return $oauth->grantAccessToken();
		} catch (OAuth2ServerException $oauthError) {
			return $oauthError->getHttpResponse();
		}
	}

}