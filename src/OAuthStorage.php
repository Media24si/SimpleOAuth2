<?php  namespace Media24si\SimpleOAuth2; 

use Media24si\SimpleOAuth2\Entities\AccessToken;
use Media24si\SimpleOAuth2\Entities\RefreshToken;
use Media24si\SimpleOAuth2\Entities\Client;

use OAuth2\IOAuth2GrantClient;
use OAuth2\IOAuth2GrantUser;
use OAuth2\IOAuth2RefreshTokens;
use OAuth2\Model\IOAuth2AccessToken;
use OAuth2\Model\IOAuth2Client;

use Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use OAuth2\Model\IOAuth2Token;

class OAuthStorage implements IOAuth2GrantClient, IOAuth2GrantUser, IOAuth2RefreshTokens {


	/**
	 * Required for OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS.
	 *
	 * @param IOAuth2Client $client The client for which to check credentials.
	 * @param string $clientSecret (optional) If a secret is required, check that they've given the right one.
	 *
	 * @return bool|array Returns true if the client credentials are valid, and MUST return false if they aren't.
	 * When using "client credentials" grant mechanism and you want to
	 * verify the scope of a user's access, return an associative array
	 * with the scope values as below. We'll check the scope you provide
	 * against the requested scope before providing an access token:
	 * @code
	 * return array(
	 *     'scope' => <stored scope values (space-separated string)>,
	 * );
	 * @endcode
	 *
	 * @see     http://tools.ietf.org/html/draft-ietf-oauth-v2-20#section-4.4.2
	 *
	 * @ingroup oauth2_section_4
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
		return Client::where('client_id', $clientId)->first();
	}

	/**
	 * Make sure that the client credentials are valid.
	 *
	 * @param IOAuth2Client $client The client for which to check credentials.
	 * @param string $clientSecret (optional) If a secret is required, check that they've given the right one.
	 *
	 * @return bool TRUE if the client credentials are valid, and MUST return FALSE if they aren't.
	 *
	 * @see     http://tools.ietf.org/html/draft-ietf-oauth-v2-20#section-3.1
	 *
	 * @ingroup oauth2_section_3
	 */
	public function checkClientCredentials(IOAuth2Client $client, $clientSecret = null)
	{
		if ($client instanceof Client) {
			return $client->checkSecret($clientSecret);
		}

		return false;
	}

	/**
	 * Look up the supplied oauth_token from storage.
	 *
	 * We need to retrieve access token data as we create and verify tokens.
	 *
	 * @param string $oauthToken The token string.
	 *
	 * @return IOAuth2AccessToken
	 *
	 * @ingroup oauth2_section_7
	 */
	public function getAccessToken($oauthToken)
	{
		return AccessToken::where('token', $oauthToken)->first();
	}

	/**
	 * Store the supplied access token values to storage.
	 *
	 * We need to store access token data as we create and verify tokens.
	 *
	 * @param string $oauthToken The access token string to be stored.
	 * @param IOAuth2Client $client The client associated with this refresh token.
	 * @param mixed $data Application data associated with the refresh token, such as a User object.
	 * @param int $expires The timestamp when the refresh token will expire.
	 * @param string $scope (optional) Scopes to be stored in space-separated string.
	 *
	 * @ingroup oauth2_section_4
	 */
	public function createAccessToken($oauthToken, IOAuth2Client $client, $data, $expires, $scope = null)
	{
		$token = new AccessToken();
		$token->client_id = $client->id;
		$token->token = $oauthToken;
		$token->expires_at = $expires;
		$token->scope = $scope;

		if ( $data instanceof Authenticatable) {
			$token->user_id = $data->getAuthIdentifier();
		}

		$token->save();

		return $token;
	}

	/**
	 * Check restricted grant types of corresponding client identifier.
	 *
	 * If you want to restrict clients to certain grant types, override this
	 * function.
	 *
	 * @param IOAuth2Client $client Client to check.
	 * @param string $grantType Grant type to check. One of the values contained in OAuth2::GRANT_TYPE_REGEXP.
	 *
	 * @return bool Returns true if the grant type is supported by this client identifier or false if it isn't.
	 *
	 * @ingroup oauth2_section_4
	 */
	public function checkRestrictedGrantType(IOAuth2Client $client, $grantType)
	{
		if (!$client instanceof Client) {
			throw new \InvalidArgumentException('Client has to implement the Client entity');
		}

		return in_array($grantType, $client->allowed_grant_types, true);
	}

	/**
	 * Grant access tokens for basic user credentials.
	 *
	 * Check the supplied username and password for validity.
	 * You can also use the $client param to do any checks required based on a client, if you need that.
	 * Required for OAuth2::GRANT_TYPE_USER_CREDENTIALS.
	 *
	 * @param IOAuth2Client $client Client to check.
	 * @param string $username Username to check.
	 * @param string $password Password to check.
	 *
	 * @return bool|array Returns true if the username and password are valid or false if they aren't.
	 * Moreover, if the username and password are valid, and you want to
	 * verify the scope of a user's access, return an associative array
	 * with the scope values as below. We'll check the scope you provide
	 * against the requested scope before providing an access token:
	 * @code
	 * return array(
	 *     'scope' => <stored scope values (space-separated string)>,
	 * );
	 * @endcode
	 *
	 * @see     http://tools.ietf.org/html/draft-ietf-oauth-v2-20#section-4.3
	 *
	 * @ingroup oauth2_section_4
	 */
	public function checkUserCredentials(IOAuth2Client $client, $username, $password)
	{
		$user_credentials = array_merge([
			config('simpleoauth2.user.username_field') => $username,
			config('simpleoauth2.user.password_field') => $password,
		], config('simpleoauth2.user.conditions'));

		if ( Auth::attempt($user_credentials) ) {
			return [
				'data' => Auth::user()
			];
		};

		return false;
	}

	/**
	 * Grant refresh access tokens.
	 *
	 * Retrieve the stored data for the given refresh token.
	 * Required for OAuth2::GRANT_TYPE_REFRESH_TOKEN.
	 *
	 * @param string $refreshToken Refresh token string.
	 *
	 * @return IOAuth2Token
	 *
	 * @see     http://tools.ietf.org/html/draft-ietf-oauth-v2-20#section-6
	 *
	 * @ingroup oauth2_section_6
	 */
	public function getRefreshToken($refreshToken)
	{
		return RefreshToken::where('token', $refreshToken)->first();
	}

	/**
	 * Take the provided refresh token values and store them somewhere.
	 *
	 * This function should be the storage counterpart to getRefreshToken().
	 * If storage fails for some reason, we're not currently checking for
	 * any sort of success/failure, so you should bail out of the script
	 * and provide a descriptive fail message.
	 * Required for OAuth2::GRANT_TYPE_REFRESH_TOKEN.
	 *
	 * @param string $refreshToken The refresh token string to be stored.
	 * @param IOAuth2Client $client The client associated with this refresh token.
	 * @param mixed $data Application data associated with the refresh token, such as a User object.
	 * @param int $expires The timestamp when the refresh token will expire.
	 * @param string $scope (optional) Scopes to be stored in space-separated string.
	 *
	 * @ingroup oauth2_section_6
	 */
	public function createRefreshToken($refreshToken, IOAuth2Client $client, $data, $expires, $scope = null)
	{
		$refToken = new RefreshToken();
		$refToken->client_id = $client->id;
		$refToken->token = $refreshToken;
		$refToken->expires_at = $expires;
		$refToken->scope = $scope;

		if ( $data instanceof Authenticatable) {
			$refToken->user_id = $data->getAuthIdentifier();
		}

		$refToken->save();

		return $refToken;
	}

	/**
	 * Expire a used refresh token.
	 *
	 * This is not explicitly required in the spec, but is almost implied. After granting a new refresh token, the old
	 * one is no longer useful and so should be forcibly expired in the data store so it can't be used again.
	 * If storage fails for some reason, we're not currently checking for any sort of success/failure, so you should
	 * bail out of the script and provide a descriptive fail message.
	 *
	 * @param string $refreshToken The refresh token string to expire.
	 *
	 * @ingroup oauth2_section_6
	 */
	public function unsetRefreshToken($refreshToken)
	{
		RefreshToken::where('token', $refreshToken)->delete();
	}

}