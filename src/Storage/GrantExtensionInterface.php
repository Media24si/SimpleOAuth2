<?php

namespace Media24si\SimpleOAuth2\Storage;

use OAuth2\Model\IOAuth2Client;

interface GrantExtensionInterface
{
    /**
     * @see OAuth2\IOAuth2GrantExtension::checkGrantExtension
     */
    public function checkGrantExtension(IOAuth2Client $client, array $inputData, array $authHeaders);
}
