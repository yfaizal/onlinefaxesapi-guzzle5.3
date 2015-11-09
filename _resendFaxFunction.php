<?php
require __DIR__ . '/vendor/autoload.php';
$configs = include('config.php');

use GuzzleHttp\Client;
use GuzzleHttp\Query;

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {
    if (isset($_POST["dtFaxId"]) && !empty($_POST["dtFaxId"])) { //Check if action value exists
        $client = new Client();
        $request = $client->createRequest('POST', $configs['base_url'].'/fax/async/resendfax', [
            'headers' => $configs['headers'],
            'verify' => true,
        ]);
        $query = $request->getQuery();
        $query['faxId'] = $_POST["dtFaxId"];
        $response = $client->send($request);
        $json = $response->json();
      // get the response
       echo 'Status: '.$json['Item1'].'; FaxId: '.$json['Item2'];
    }
}


