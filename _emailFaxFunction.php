<?php
require __DIR__ . '/vendor/autoload.php';
$configs = include('config.php');

use GuzzleHttp\Client;
use GuzzleHttp\Query;

$faxRecipientList = array();


function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {

    if (isset($_POST["dtFaxId"]) && !empty($_POST["dtFaxId"])) { //Check if action value exists

        $objStd = json_decode($_POST['faxRecipientEmailList']);

        foreach($objStd->faxRecipientEmailList as $object) {
            $faxRecipientObj = array('Name'=> $object->Name, 'EmailAddress'=> $object->EmailAddress);
            array_push($faxRecipientList,$faxRecipientObj);
        }
        $json_faxRecipientList = json_encode($faxRecipientList);

        $client = new Client();
        $request = $client->createRequest('POST', $configs['base_url'].'/fax/async/emailfax', [
            'headers' => $configs['headers'],
            'verify' => true,
            'query' => ['faxRecipientEmailList' => $json_faxRecipientList,
                'faxId' => $_POST["dtFaxId"]
            ]
        ]);
        // send request
        try {

            $response = $client->send($request);
            //  var_dump($response);exit;
            // Here the code for successful request
            $json = $response->json(); //var_dump($json);
            $completeComplexmodelStatus =  1;
            //   echo 'success';
            echo 'Status: '.$json['Item1'].'; FaxId: '.$json['Item2'];
        } catch (RequestException $e) {
            // Catch all 4XX errors
            // To catch exactly error 400 use
            /*            if ($e->getResponse()->getStatusCode() == '400') {
                            echo "Got response 400";
                        }*/
            //     echo '400';
            $errors[] = $e->getResponse()->getStatusCode();
            //$alertStyle = 'alert-danger';
            // You can check for whatever error status code you need
        } catch (\Exception $e) {
            //    echo 'other errors';
            $errors[] = $e;
         //   $alertStyle = 'alert-danger';
            // There was another exception.
        }

        if(!empty($errors)) {
            foreach ($errors as $error) {
                echo '<p class="form-control-static" style="color: red">' . $error . '</p></ br>\n';
            }
        }

    }
}


