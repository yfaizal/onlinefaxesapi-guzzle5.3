Guzzle5.3 Code Samples
=================


Get The Access Token
    .. code-block:: php

        use GuzzleHttp\Client;
        use CommerceGuys\Guzzle\Oauth2\Oauth2Subscriber;
        $base_url = 'https://api.onlinefaxes.com/v2/';
        $oauth2Client = new Client(['base_url' => $base_url]);

        $config = [
        'client_id' => 'YOUR_ID',
        'client_secret' => 'YOUR SECRET',
        'grant_type' => 'client_credentials',
        'scope' => null,
        ];

        $accessToken = new CommerceGuys\Guzzle\Oauth2\GrantType\ClientCredentials($oauth2Client, $config);
        $oauth2 = new Oauth2Subscriber($accessToken);
        
        // response data
        $response = $oauth2->getAccessToken();
        // return token
        $response->getToken()
        // return expiry date ie. 2015-10-02 18:56:20
        $response->getExpires()->format('Y-m-d H:i:s')
        // return default expiry timezone ie America/Chicago
        $response->getExpires()->getTimezone()->getName()
