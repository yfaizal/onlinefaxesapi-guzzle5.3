<!-- HEADER
================================================== -->
<?php
// php upload sessions progress
session_start();
require("_header.php");
require("_check_token.php");
?>

<link rel="stylesheet" type="text/css" href="css/button-style.css">

<div class="container-fluid">

<?php
require("_dashboard-menu.php");
?>

<h3><span class="label label-default">Send Fax</span></h3>

<?php
// default var setting
$fileCount = 0; // default without file attachment
$headerTextNotification = 'API Response';
$alertStyle = 'alert-info';
$proceedToFax = 0;
$completeResponse = '';
$completeSimplemodelStatus = 0;
$errorResponse = 0;
$errors = [];

use GuzzleHttp\Client;
use GuzzleHttp\Query;
use GuzzleHttp\Post\PostFile;
use GuzzleHttp\Pool;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;

if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){

    $faxRecipientList = array();

    foreach($_POST['recipient'] as $key=>$item)
    {
        if(($_POST['recipient'][$key]['Name'] != '') && ($_POST['recipient'][$key]['FaxNumber'] != '') ){
            $faxRecipientObj = array('recipientName'=> $_POST['recipient'][$key]['Name'], 'recipientFaxNo'=>$_POST['recipient'][$key]['FaxNumber']);
            array_push($faxRecipientList,$faxRecipientObj);
        }
    }

    // 1. check empty recipient list
    if (empty($faxRecipientList))
    {
        $errorMessage[] = 'No recipient & fax number! .';
        $headerTextNotification = 'Send Fax Error !';
        $alertStyle = 'alert-danger';
    }

    // 2. File attachment
    if (empty($errorMessage))
    {
        $uploadFiles = array();
        // Loop $_FILES to execute all files
        foreach ($_FILES['faxfile']['name'] as $f => $name) {
            if ($_FILES['faxfile']['error'][$f] == 4) {
                continue; // Skip file if any error found
            }
            if ($_FILES['faxfile']['error'][$f] == 0) {
                if ($_FILES['faxfile']['size'][$f] > $configs['max_file_size']) {
                    $errorMessage[] = "$name is too large!.";
                    $alertStyle = 'alert-danger';
                    $headerTextNotification = 'Send Fax Error !';
                    continue; // Skip large files
                }
                elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $configs['file_upload_valid_formats']) ){
                    $errorMessage[] = "$name is not a valid format";
                    $alertStyle = 'alert-danger';
                    $headerTextNotification = 'Send Fax Error !';
                    continue; // Skip invalid file formats
                }
                else{ // No error found, so move uploaded files
                    if(move_uploaded_file($_FILES["faxfile"]["tmp_name"][$f], $configs['filePath'].$name)) {
                        $fileCount++; // Number of successfully uploaded files
                        $uploadFiles[] = $configs['filePath'].$name;
                    }
                }
            }
        }
    }


    $client = new Client();

    if(empty($errorMessage) && ($fileCount == 0)){ // request to /simplemodel

        // generate requests array
        foreach($faxRecipientList as $key=>$detail)
        {
            $requests[] = $client->createRequest('POST', $configs['base_url'].'/fax/async/sendfax/simplemodel', [
                'headers' => $configs['headers'],
                'verify' => true, // cert
                'query' => ['senderName' => $_POST['inputSenderName'],
                    'senderCompanyName' => $_POST['inputSenderCompanyName'],
                    'faxSubject' => $_POST['inputFaxSubject'],
                    'faxNotes' => $_POST['inputFaxNotes'],
                    'recipientName' => $detail['recipientName'],
                    'recipientFaxNo' => $detail['recipientFaxNo']
                ]
            ]);
        }
        $proceedToFax = 1;

        // use Guzzle pool to send fax
        Pool::send($client, $requests, [
            'complete' => function (CompleteEvent $event) {
                //  echo 'Completed request to ' . $event->getRequest()->getUrl() . "\n";
              //  echo 'Response: ' . $event->getResponse()->getBody() . "\n\n";
                $completeResponse =  $event->getResponse();
                $completeSimplemodelStatus = 1;
            },
            'error' => function (ErrorEvent $event) use (&$errors) {
               // $errorResponse = 1;
                $errors[] = $event;
            }
        ]);


    /*
       foreach ($errors as $error) {
            var_dump($error);
        }*/

    }elseif(empty($errorMessage) && ($fileCount != 0)){ // request to /complexmodel
        // convert recipients array to json
        // atm querystring parameters are different between /simplemodel and /complexmodel so need to create it again here
        $faxRecipientList2 = array();

        foreach($faxRecipientList as $key=>$item)
        {
                $faxRecipientObj = array('Name'=> $item['recipientName'], 'FaxNumber'=>$item['recipientFaxNo']);
                array_push($faxRecipientList2,$faxRecipientObj);
        }

        $json_faxRecipientList2 = json_encode($faxRecipientList2);

        $faxSenderObj = array(
            'Id' => '0',
            'Name'=> $_POST['inputSenderName'],
            'Company'=> $_POST['inputSenderCompanyName'],
            'Subject'=> $_POST['inputFaxSubject'],
            'Notes'=> $_POST['inputFaxNotes']
        );
        $json_faxSenderObj = json_encode($faxSenderObj);

        $proceedToFax = 1;

        // prepare fax

        $request = $client->createRequest('POST', $configs['base_url'].'/fax/async/sendfax/complexmodel', [
            'headers' => $configs['headers'],
            'verify' => true, // cert
        ]);
        $postBody = $request->getBody();
        $postBody->setField('RecipientList', $json_faxRecipientList2);
        $postBody->setField('SenderDetail', $json_faxSenderObj);

        // attach file to fax
        foreach($uploadFiles as $key => $value) {
            // $postBody->addFile(new PostFile('file_1', fopen('SampleFaxDoc.doc', 'r')));
            $postBody->addFile(new PostFile('file_'.$key, fopen($value, 'r')));
        }
       // var_dump($request->getBody());exit;
        // send request
        try {

            $response = $client->send($request);
          //  var_dump($response);exit;
            // Here the code for successful request
            $json = $response->json(); //var_dump($json);
            $completeComplexmodelStatus =  1;
         //   echo 'success';

        } catch (RequestException $e) {
            // Catch all 4XX errors
            // To catch exactly error 400 use
/*            if ($e->getResponse()->getStatusCode() == '400') {
                echo "Got response 400";
            }*/
        //     echo '400';
            $errors[] = $e->getResponse()->getStatusCode();
            $alertStyle = 'alert-danger';
            // You can check for whatever error status code you need
        } catch (\Exception $e) {
        //    echo 'other errors';
            $errors[] = $e;
            $alertStyle = 'alert-danger';
            // There was another exception.
        }

    }


    ?>
    <!-- response div -->
    <div class="col-sm-6 col-md-8 col-md-offset-2">
        <div class="alert <?php echo $alertStyle;?>" role="alert">
            <form class="form-horizontal" id="returnResponse" role="form" action="">
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                            <h3><?php echo $headerTextNotification;?></h3>
                    </div>
                </div>

                <?php if($proceedToFax == 0){

                    foreach ($errorMessage as $msg) {
                        printf("<div><p class='status'>%s</p></div></ br>\n", $msg);
                    }

                 }else{?>

                <div class="row">
                    <div class="col-sm-6 col-md-6">

                        <div class="col-md-8 col-md-offset-4">
                            <?php if(!empty($errors)){
                                foreach ($errors as $error) {
                                    echo '<p class="form-control-static">'.$error.'</p></ br>\n';
                                }
                            }elseif($completeSimplemodelStatus==1){?>

                            <p class="form-control-static"><?php echo $completeResponse;?></p>

                            <?php } elseif($completeComplexmodelStatus==1){?>

                            <p class="form-control-static">Status: <?php echo $json['Item1'];?></p>
                            <p class="form-control-static">Fax ID: <?php echo $json['Item2'];?></p>

                            <?php } ?>
                        </div>

                    </div>

                </div><!-- row end -->
                <?php } ?>

            </form>
        </div><!-- alert end -->
    </div>

<?php } // end post ?>

<form class="form-horizontal" id="myForm" role="form" action="" method="post" enctype="multipart/form-data">

    <input type="hidden" value="myForm" name="<?php echo ini_get("session.upload_progress.name"); ?>">
    <div class="row">

        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">&nbsp;</label>
                <div class="col-md-8">
                    <h3>Sender's Info</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">&nbsp;</label>
                <div class="col-md-8">
                    <h3>Recipient's Info</h3>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label for="inputSenderName" class="col-md-4 control-label">Sender Name:</label>
                <div class="col-md-8">
                    <input class="form-control" id="inputSenderName" name="inputSenderName" placeholder="Sender Name" type="text" value="Sender's Name">
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label for="inputRecipient1Name" class="col-md-4 control-label">Name of Rec. 1:</label>
                <div class="col-md-8">
                    <input class="form-control" id="inputRecipient1Name" name="recipient[0][Name]" placeholder="Name of Recipient 1" type="text">

                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label for="inputSenderCompanyName" class="col-md-4 control-label">Company Name:</label>
                <div class="col-md-8">

                    <input class="form-control" name="inputSenderCompanyName" id="inputSenderCompanyName" placeholder="Sender Company Name" type="text" value="Sender's Company Name">
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label for="Recipient1FaxNo" class="col-md-4 control-label">Rec. 1 Fax No:</label>
                <div class="col-md-8">
                    <input class="form-control" id="Recipient1FaxNo" name="recipient[0][FaxNumber]" placeholder="Fax No. of Recipient 1" type="text">
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label for="inputFaxSubject" class="col-md-4 control-label">Fax Subject:</label>
                <div class="col-md-8">
                    <input class="form-control" name="inputFaxSubject" id="inputFaxSubject" placeholder="Fax Subject" type="text" value="Fax Subject">
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label for="inputRecipient2Name" class="col-md-4 control-label">Name of Rec. 2:</label>
                <div class="col-md-8">
                    <input class="form-control" id="inputRecipient2Name" name="recipient[1][Name]" placeholder="Name of Recipient 2" type="text">

                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label for="inputFaxNotes" class="col-md-4 control-label">Fax Notes:</label>
                <div class="col-md-8">
                    <textarea id="inputFaxNotes" name="inputFaxNotes" placeholder="You can replace this textarea with any WYSIWYG Editor like Summernote." class="form-control" rows="4">Fax Notes from textarea</textarea>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label for="Recipient2FaxNo" class="col-md-4 control-label"> Rec. 2 Fax No: </label>
                <div class="col-md-8">
                    <input class="form-control" id="Recipient2FaxNo" name="recipient[1][FaxNumber]" placeholder="Fax No. of Recipient 2" type="text">
                </div>
            </div>
        </div>
    </div><!-- ./row -->

    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label for="inputSenderName" class="col-md-4 control-label">&nbsp;</label>
                <div class="col-md-8">
                    <h3>File(s) Attachment</h3>
                </div>
            </div>
        </div>
    </div><!-- ./row -->

    <div class="row">
        <div class="col-sm-6 col-md-6 col-md-offset-2">
            <div id="bar_blank">
                <div id="bar_color"></div>
            </div>
            <div id="status"></div>

        </div>

    </div><!-- ./row -->

    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">File Attachment 1:</label>
                <div class="col-md-8">
                    <div class="input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-warning btn-file">
                                    Browse <input name="faxfile[]" type="file" multiple aria-describedby="file2">
                                </span>
                            </span>
                        <input type="text" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- ./row -->
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">File Attachment 2:</label>
                <div class="col-md-8">
                    <div class="input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-warning btn-file">
                                    Browse <input name="faxfile[]" type="file" multiple aria-describedby="file2">
                                </span>
                            </span>
                        <input type="text" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- ./row -->
    <div class="row">
        <div class="col-sm-6 col-md-6 col-md-offset-2">

            <button type="submit" class="btn btn-primary">Send</button>

        </div>
    </div> <!-- ./row -->

</form>


</div><!-- /.container -->

<br>
<!-- FOOTER
================================================== -->
<?php require("_footer.php"); ?>

<script>

    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready( function() {
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                    log = numFiles > 1 ? numFiles + ' files selected' : label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });
    });

</script>
<script type="text/javascript" src="scripts/upload-progress.js"></script>

