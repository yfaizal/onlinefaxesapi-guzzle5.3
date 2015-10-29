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

**Get Fax Status**

    .. code-block:: php
    
        use GuzzleHttp\Client;
        
        $client = new Client();
        $request = $client->createRequest('POST', 'https://api.onlinefaxes.com/v2/fax/async/getfaxstatus', [
        'headers' => ['Content-Type' => 'application/x-www-form-urlencoded',
                       'Authorization' => 'ofx YOUR ACCESS TOKEN'],
        'query' => ['faxId' => '012345678901'],
        ]);
        // send request
        $response = $client->send($request);
        
        // get response in json
        $json = $response->json();
        // will return status i.e 'Completed'
        echo $json['Status'];
   
**Download Fax**

    .. code-block:: php
    
        use GuzzleHttp\Client;
        use GuzzleHttp\Query;
        
        $client = new Client();
        $request = $client->createRequest('POST', 'https://api.onlinefaxes.com/v2/fax/async/downloadfaxfile', [
        'headers' => ['Content-Type' => 'application/x-www-form-urlencoded',
                   'Authorization' => 'ofx YOUR ACCESS TOKEN'],
        'query' => ['faxId' => '012345678901'],
        ]);
        // send request and get response
        $response = $client->send($request);
        
        // get response in json
        $json = $response->json();
        // will return Url for fax download
        echo $json['Status'];

**Delete Fax**

    .. code-block:: php
    
        use GuzzleHttp\Client;
        use GuzzleHttp\Query;
        
        $client = new Client();
        $request = $client->createRequest('POST', 'https://api.onlinefaxes.com/v2/fax/async/deletefax', [
        'headers' => ['Content-Type' => 'application/x-www-form-urlencoded',
                   'Authorization' => 'ofx YOUR ACCESS TOKEN'],
                   'verify' => false, // disable cert
        ]);
        $query = $request->getQuery();
        $query['faxId'] = '1234567890';
        // send request
        $response = $client->send($request);
        // get response in json
        $json = $response->json();
        // return response
        echo $json['Status'];

**Get Fax Detail**

    .. code-block:: php
    
        use GuzzleHttp\Client;

        $client = new Client();
        $request = $client->createRequest('POST', 'https://api.onlinefaxes.com/v2/fax/async/getfaxdetail', [
        'headers' => ['Content-Type' => 'application/x-www-form-urlencoded',
                       'Authorization' => 'ofx YOUR ACCESS TOKEN'],
        'query' => ['faxId' => '012345678901'],
        ]);
        // send request and get response
        $response = $client->send($request);
        // get response in json
        $json = $response->json();
        // get Fax sending status i.e 'Completed'
        echo $json['MessageDetails']['Status'];
        // get Fax Transaction Id
        echo $json['MessageDetails']['Transactions']['TransactionDetails']['TransactionID'];
        // get all ['MessageDetails'] response
        foreach($json['MessageDetails'] as $key=>$value) { //foreach element in $arr
        echo $key.' = '.$value.'
        '; //etc
        }
        
*Note : Please check this link <https://onlinefaxes.readme.io/docs/get-fax-details> for a full lists of response data.*

**Get Fax List**

    .. code-block:: php
    
        use GuzzleHttp\Client;
        $client = new Client();
        // get response in json
        $json = $response->json();
        // loop through the json data
        foreach($json as $key=>$value) { //foreach element in $json
        echo $key.' = '.$value['Id']; // $value['Subject'],$value['RecpName'] etc.
        }
        // set folderId
        $setFolderId = '1001'; // Inbox(1001),Processing(1002),Sent(1003),Failed(1004),Deleted(1007)
        $request = $client->createRequest('POST', 'https://api.onlinefaxes.com/v2/fax/async/getfaxlist', [
        'headers' => ['Content-Type' => 'application/x-www-form-urlencoded',
                       'Authorization' => 'ofx YOUR ACCESS TOKEN'],
        'query' => ['folderId' => $setFolderId, 'isDownloaded' => 'true'],,
        ]);
        // send request and get response
        $response = $client->send($request);
        
        // get response in json
        $json = $response->json();
        // loop through the json data
        foreach($json as $key=>$value) { //foreach element in $json
        echo $key.' = '.$value['Id']; // $value['Subject'],$value['RecpName'] etc.
        }
