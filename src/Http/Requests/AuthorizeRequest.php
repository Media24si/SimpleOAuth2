<?php

namespace Media24si\SimpleOAuth2\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OAuth2\OAuth2;
use OAuth2\OAuth2ServerException;
use Illuminate\Contracts\Validation\Validator;

class AuthorizeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_id'     => 'required',
            'response_type' => 'required',
            'redirect_uri'  => 'required|url'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, OAuth2::ERROR_INVALID_REQUEST,
            'Invalid grant_type parameter or parameter missing');
    }
}
