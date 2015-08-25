<?php

namespace Media24si\SimpleOAuth2\Entities;

use OAuth2\Model\IOAuth2AuthCode;

class AuthCode extends Token implements IOAuth2AuthCode
{

    protected $table = 'oauth_authCode';

    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }

}
