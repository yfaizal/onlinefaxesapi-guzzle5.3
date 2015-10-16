<?php
require __DIR__ . '/vendor/autoload.php';
$configs = include('config.php');

use GuzzleHttp\Client;
use GuzzleHttp\Query;

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {
    if (isset($_POST["dtFaxId"]) && !empty($_POST["dtFaxId"])) { //Checks if action value exists
        $client = new Client();
        $request = $client->createRequest('POST', $configs['base_url'].'/fax/async/downloadfaxfile', [
            'headers' => $configs['headers'],
            'verify' => true,
        ]);
        $query = $request->getQuery();
        $query['faxId'] = $_POST["dtFaxId"];
        $response = $client->send($request);
        $json = $response->json();
      // get response
       echo $json['Status'];
    }
}


