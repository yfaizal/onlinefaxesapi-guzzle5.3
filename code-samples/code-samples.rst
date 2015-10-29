Guzzle5.3 Code Samples
======================

**Get The Access Token**

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
    
**Send Fax - Complex Model**

    .. code-block:: php

        use GuzzleHttp\Client;
        use GuzzleHttp\Query;
        use GuzzleHttp\Post\PostFile;

        $json_faxRecipientList =
        [
        {
        "Name": "Name Recipient 1",
        "FaxNumber": "1234567890"
        },
        {
        "Name": "Name Recipient 2",
        "FaxNumber": "1234567892"
        }
        ]
    
         $json_faxSenderObj =
        {
        "Id": "0",
        "Name": "Sender Name",
        "Company": "Sender Company Name",
        "Subject": "Fax Subject",
        "Notes": "Fax notes"
        

         $client = new Client();
    
        $request = $client->createRequest('POST', 'https://api.onlinefaxes.com/v2/fax/async/sendfax/complexmodel','headers' => ['Content-Type' => 'application/x-www-form-urlencoded',
                 'Authorization' => 'ofx YOUR ACCESS TOKEN'],
        'verify' => false, // cert
        ])
    
        $postBody = $request->getBody();
        $postBody->setField('RecipientList', $json_faxRecipientList);
        $postBody->setField('SenderDetail', $json_faxSenderObj);
        $postBody->addFile(new PostFile('file_1', fopen('SampleDoc1.doc', 'r')));
        $postBody->addFile(new PostFile('file_2', fopen('SampleDoc2.doc', 'r')));
    
        // send request
        $response = $client->send($request);
        // get response in json
        $json = $response->json();
        var_dump($json);
