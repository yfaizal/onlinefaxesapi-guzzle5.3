<?php
        // make a simple request just to check the response for expired token
        use GuzzleHttp\Client;
        $clientToken = new Client();

$requestStatus = $clientToken->createRequest('POST','https://api.onlinefaxes.com/v2/fax/async/getfaxstatus', [
    'headers' => $configs['headers'],
    'query' => ['faxId' => '208121736570'],
    'verify' => true,
]);
$responseStatus = $clientToken->send($requestStatus);
        $checkUri = $responseStatus->getEffectiveUrl();
        // at the moment of this release, when the token expired, the response is not in json data but instead an URL to login. So use Guzzle getEffectiveUrl() to
        // check return Url.
        if(strpos($checkUri,'Login')!== false ){
        // token had expired;
         $tokenBtn = 'btn-danger';
         $tokenIcon = 'glyphicon-remove';

            if(strpos($_SERVER['REQUEST_URI'],'get-token')!== false ){
                // do nothing
            }else{
                header("Location: get-token.php");
                exit();
            }

        }else{
       // token still valid;
         $tokenBtn = 'btn-success';
         $tokenIcon = 'glyphicon-ok';
        }
         $tokenText = 'Token'; // text button
?>
