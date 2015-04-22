<?php namespace Media24si\SimpleOAuth2\Http\Middleware;

use Closure;
use OAuth2\OAuth2;
use OAuth2\OAuth2ServerException;

class SimpleOAuth2 {

	/**
	 * @var OAuth2
	 */
	private $oauth;

	/**
	 * Authenticate constructor.
	 */
	public function __construct(OAuth2 $oauth)
	{
		$this->oauth = $oauth;
	}


	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		try {
			$token = $this->oauth->getBearerToken();
			$this->oauth->verifyAccessToken($token);
		} catch (OAuth2ServerException $oauthError) {
			return $oauthError->sendHttpResponse();
		}


		return $next($request);
	}

}
