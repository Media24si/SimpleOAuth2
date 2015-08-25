<?php

namespace Media24si\SimpleOAuth2\Http\Controllers;

use Media24si\SimpleOAuth2\Entities\Client;
use Media24si\SimpleOAuth2\Http\Requests\AuthorizeRequest;
use Illuminate\Routing\Controller as BaseController;
use OAuth2\OAuth2;
use OAuth2\OAuth2ServerException;

class AuthorizeController extends BaseController
{

    public function authorize(OAuth2 $oauth, AuthorizeRequest $request)
    {

        $client = Client::find($request->input('client_id'));

        if ( null == $client) {
            throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, OAuth2::ERROR_INVALID_CLIENT, 'Unknown client');
        }

        if ($request->isMethod('post')) {
            try {
                $response = $oauth->finishClientAuthorization(true, \Auth::user()->id);
                return $response;
            } catch (OAuth2ServerException $e) {
                return $e->getHttpResponse();
            }
        }

        return view('SimpleOAuth2::authorize')
                ->with('client', $client)
                ->with('auth_params', $request->only(['client_id', 'redirect_uri', 'response_type', 'state', ]));
    }

}
