<?php

namespace Media24si\SimpleOAuth2\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use OAuth2\OAuth2;
use OAuth2\OAuth2ServerException;

class TokenController extends BaseController
{

    public function token(OAuth2 $oauth, Request $request)
    {
        try {
            return $oauth->grantAccessToken($request);
        } catch (OAuth2ServerException $oauthError) {
            return $oauthError->getHttpResponse();
        }
    }

}
