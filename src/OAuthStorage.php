<?php

namespace Media24si\SimpleOAuth2;

use Media24si\SimpleOAuth2\Entities\AccessToken;
use Media24si\SimpleOAuth2\Entities\RefreshToken;
use Media24si\SimpleOAuth2\Entities\Client;
use Media24si\SimpleOAuth2\Entities\AuthCode;

use OAuth2\OAuth2;
use OAuth2\IOAuth2GrantCode;
use OAuth2\IOAuth2GrantImplicit;
use OAuth2\IOAuth2GrantClient;
use OAuth2\IOAuth2GrantUser;
use OAuth2\IOAuth2GrantExtension;
use OAuth2\IOAuth2RefreshTokens;
use OAuth2\OAuth2ServerException;

use OAuth2\Model\IOAuth2Client;

use Auth;

class OAuthStorage implements IOAuth2GrantClient, IOAuth2GrantUser, IOAuth2RefreshTokens, IOAuth2GrantExtension, IOAuth2GrantCode, IOAuth2GrantImplicit
{

    /**
     * Grant extensions (custom grant types)
     * @var array
     */
    private $grant_extensions;

    public function __construct()
    {
        $this->grant_extensions = config('simpleoauth2.grant_extensions');
    }

    /**
     * {@inheritdoc}
     */
    public function checkClientCredentialsGrant(IOAuth2Client $client, $clientSecret)
    {
        return $this->checkClientCredentials($client, $clientSecret);
    }

    /**
     * Get a client by its ID.
     *
     * @param string $clientId
     *
     * @return IOAuth2Client
     */
    public function getClient($clientId)
    {
        return Client::find($clientId);
    }

    /**
     * {@inheritdoc}
     */
    public function checkClientCredentials(IOAuth2Client $client, $clientSecret = null)
    {
        if ($client instanceof Client) {
            return $client->checkSecret($clientSecret);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthCode($code)
    {
        return AuthCode::where('token', $code)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function markAuthCodeAsUsed($code)
    {
        AuthCode::where('token', $code)->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function createAuthCode($code, IOAuth2Client $client, $data, $redirectUri, $expires, $scope = null)
    {

        $authCode = new AuthCode();

        $authCode->client_id = $client->id;
        $authCode->token = $code;
        $authCode->expires_at = $expires;
        $authCode->scope = $scope;
        $authCode->redirect_uri = $redirectUri;

        $authCode->setData($data);

        $authCode->save();

        return $authCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken($oauthToken)
    {
        return AccessToken::where('token', $oauthToken)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function createAccessToken($oauthToken, IOAuth2Client $client, $data, $expires, $scope = null)
    {

        $token = new AccessToken();
        $token->client_id = $client->id;
        $token->token = $oauthToken;
        $token->expires_at = $expires;
        $token->scope = $scope;

        $token->setData($data);

        $token->save();

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function checkRestrictedGrantType(IOAuth2Client $client, $grantType)
    {
        if (!$client instanceof Client) {
            throw new \InvalidArgumentException('Client has to implement the Client entity');
        }

        return in_array($grantType, $client->allowed_grant_types, true);
    }

    /**
     * {@inheritdoc}
     */
    public function checkUserCredentials(IOAuth2Client $client, $username, $password)
    {
        $user_credentials = array_merge([
            config('simpleoauth2.user.username_field') => $username,
            config('simpleoauth2.user.password_field') => $password,
        ], config('simpleoauth2.user.conditions'));

        if (Auth::attempt($user_credentials)) {
            return [
                'data' => Auth::user()
            ];
        };

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getRefreshToken($refreshToken)
    {
        return RefreshToken::where('token', $refreshToken)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function createRefreshToken($refreshToken, IOAuth2Client $client, $data, $expires, $scope = null)
    {
        $refToken = new RefreshToken();
        $refToken->client_id = $client->id;
        $refToken->token = $refreshToken;
        $refToken->expires_at = $expires;
        $refToken->scope = $scope;

        $refToken->setData($data);

        $refToken->save();

        return $refToken;
    }

    /**
     * {@inheritdoc}
     */
    public function unsetRefreshToken($refreshToken)
    {
        RefreshToken::where('token', $refreshToken)->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function checkGrantExtension(IOAuth2Client $client, $uri, array $inputData, array $authHeaders)
    {
        if (!isset($this->grant_extensions[$uri]) || !class_exists($this->grant_extensions[$uri])) {
            throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, OAuth2::ERROR_UNSUPPORTED_GRANT_TYPE);
        }

        $grant_extension = new $this->grant_extensions[$uri];
        if (!is_a($grant_extension, 'Media24si\SimpleOAuth2\Storage\GrantExtensionInterface')) {
            throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, OAuth2::ERROR_UNSUPPORTED_GRANT_TYPE);
        }

        return $grant_extension->checkGrantExtension($client, $inputData, $authHeaders);
    }

}
