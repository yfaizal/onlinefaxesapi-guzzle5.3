<!-- HEADER
================================================== -->
<?php
require("_header.php");
require("_check_token.php");
?>

<div class="container-fluid">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.css"/>

    <?php
    require("_dashboard-menu.php");

    // check selected folder - to change text
    switch ($setFolderId) {
        case '1001':
            $listsText = 'Received Fax Lists';
        break;
        case '1002':
            $listsText = 'Processing Fax Lists';
        break;
        case '1003':
            $listsText = 'Sent Fax Lists';
        break;
        case '1004':
            $listsText = 'Failed Fax Lists';
        break;
        case '1007':
            $listsText = 'Deleted Fax Lists';
        default:
            $listsText = 'Received Fax Lists';
    }
    ?>

    <h3><span class="label label-default"><?php echo $listsText;?></span></h3>

    <?php

    use GuzzleHttp\Client;
    use GuzzleHttp\Query;

    $client = new Client();
    $request = $client->createRequest('POST', $configs['base_url'].'/fax/async/getfaxlist', [
        'headers' => $configs['headers'],
        'query' => ['folderId' => $setFolderId, 'isDownloaded' => 'true'],
        'verify' => true
    ]);

    $response = $client->send($request);

    /*
    $body = $response->getBody();
    while (!$body->eof()) {
        echo $body->read(1024);
    }
    */
    $body = $response->getBody();

    ?>
<div class="row">
    <div class="col-md-12 col-sm-12">
    <table id="TblFaxList_id" class="display responsive tablet-l cell-border compact ">
        <thead>
        <tr>
            <th class="dt-head-center tablet-l">No.</th>
            <th class="dt-head-center tablet-l">Fax Id</th>
            <th class="dt-head-center">Recipient Name</th>
            <th class="dt-head-center">Subject</th>
            <th class="dt-head-center">Created Date</th>
            <th class="dt-head-center">Status</th>
            <th class="dt-head-center">Error Msg</th>
            <th class="dt-head-center">Action</th>

        </tr>
        </thead>

    </table>
    </div>
</div>
</div><!-- /.container -->


<!-- FOOTER
================================================== -->
<?php require("_footer.php"); ?>



<!-- CONFIRM DELETE MODAL -->
<div class="modal fade" id="modalConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm Delete This Fax?</h4>
            </div>
            <div class="modal-body">
                <div id="delete-notification"></div>
                <div id="delete-waiting"></div>
                <div id="delete-result"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="btnModalCancelDelete" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="cfmDeleteBtn">Delete</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- VIEW DETAILS MODAL -->
<div class="modal fade" id="modalFaxDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Fax Details:</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form class="form-horizontal">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Fax Id :</label>
                                <div class="col-sm-6">
                                    <div id="modal-details-faxid"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Date Created :</label>

                                <div class="col-sm-6">
                                    <div id="modal-details-CreatedDate"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Sender Name :</label>
                                <div class="col-sm-6">
                                    <div id="modal-details-SenderName"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Is Read :</label>

                                <div class="col-sm-6">
                                    <div id="modal-details-IsRead"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Subject :</label>

                                <div class="col-sm-9">
                                    <div id="modal-details-Subject"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Recepient Name :</label>
                                <div class="col-sm-6">
                                    <div id="modal-details-RecpName"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Recipient Address :</label>

                                <div class="col-sm-6">
                                    <div id="modal-details-RecpAddress"></div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Message Status :</label>
                                <div class="col-sm-6">
                                    <div id="modal-details-MsgStatus"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Page Count :</label>

                                <div class="col-sm-6">
                                    <div id="modal-details-PageCount"></div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

            </div> <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- DOWNLOAD MODAL -->
<div class="modal fade" id="modalFaxDownload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Download Fax:</h4>
            </div>
            <div class="modal-body">
                <div id="download-notification"></div>
                <div id="api-download">

                </div>

                <div class="row">
                    <form class="form-horizontal">

                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <p class="form-control-static sdownload">
                                    </p>
                                </div>
                            </div>

                        </div>


                    </form>
                </div>

            </div> <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- CONFIRM RESEND MODAL -->
<div class="modal fade" id="modalConfirmResend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm To Resend This Fax?</h4>
            </div>
            <div class="modal-body">
                <div id="resend-notification"></div>
                <div id="resend-waiting"></div>
                <div id="resend-result"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="btnModalCancelResend" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="cfmResendBtn">Resend Fax</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- CONFIRM EMAIL MODAL -->

<div class="modal fade" id="modalConfirmEmail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" id="emailFaxForm" name="emailFaxForm" action="" method="post">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Send This Fax As An Email Attachment?</h4>
            </div>
            <div class="modal-body">
                <div id="email-notification"></div>
                <div id="email-waiting"></div>
                <div id="email-result"></div>
                <!-- form for recipient list -->

                    <div class="row">

                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="inputSenderName" class="col-md-4 control-label">Name of Rec. 1:</label>
                                <div class="col-md-8">
                                    <input class="form-control" id="inputRecipient1Name" name="faxRecipientEmailList[0][Name]" placeholder="Name of Recipient 1" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="inputRecipient1Name" class="col-md-4 control-label">Rec. 1 Email:</label>
                                <div class="col-md-8">
                                    <input class="form-control" id="Recipient1FaxNo" name="faxRecipientEmailList[0][EmailAddress]" placeholder="Email add. of Recipient 1" type="text">
                                </div>
                            </div>
                        </div>

                    </div><!-- ./row -->

                    <div class="row">


                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="inputSenderName" class="col-md-4 control-label">Name of Rec. 2:</label>
                                <div class="col-md-8">
                                    <input class="form-control" id="inputRecipient2Name" name="faxRecipientEmailList[1][Name]" placeholder="Name of Recipient 2" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="inputRecipient1Name" class="col-md-4 control-label">Rec. 2 Email:</label>
                                <div class="col-md-8">
                                    <input class="form-control" id="Recipient2FaxNo" name="faxRecipientEmailList[1][EmailAddress]" placeholder="Email add. of Recipient 2" type="text">

                                </div>
                            </div>
                        </div>

                    </div><!-- ./row -->
                    <div class="row">
                    <div class="col-sm-6 col-md-6 col-md-offset-2">

                        <!--<button type="submit" class="btn btn-primary">Send</button>-->

                    </div>
                     </div> <!-- ./row -->



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="btnModalCancelEmail" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="cfmEmailBtn">Send</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.js"></script>

<script type="text/javascript">
    //////////////////////////////////////////////////////////////////////////
    //////////     Base DataTables     //////////
    //////////////////////////////////////////////////////////////////////
var dataSet = <?php echo $body;?>;
    DT_FaxList = $('#TblFaxList_id').DataTable(
            {
                "processing": true,
                "serverSide": false,
                "responsive": true,
                "dom": 'Rrftipl',
                "data": dataSet,
                columns: [
                    { data: 'RowNum', className: "dt-body-right", "width": "3%" },
                    { data: 'Id', className: "dt-body-center" },
                    { data: 'RecpName', className: "dt-body-left"},
                    { data: 'Subject', className: "dt-body-left" },
                    { data: 'CreatedDate', className: "dt-body-center"},
                    { data: 'MsgStatus', className: "dt-body-center", "width": "12%"},
                    { data: 'MsgStatusErrInfo', className: "dt-body-center", "width": "12%"},
                    {
                        "data": null,className:"dt-body-center", "width": "17%",
                        "defaultContent": '<a data-toggle="modal" data-target="#modalFaxDetails" class="btn btn-info btn-xs viewIco"  data-toggle="tooltip" data-placement="left" title="View Fax Detail"><span class="glyphicon glyphicon-info-sign"></span></a>  <a data-toggle="modal" data-target="#modalFaxDownload" class="btn btn-success btn-xs downloadIco" data-toggle="tooltip" data-placement="left" title="Download Fax"><span class="glyphicon glyphicon-download-alt"></span></a> <a data-toggle="modal"  data-target="#modalConfirmResend" class="btn btn-warning btn-xs resendIco" data-toggle="tooltip" data-placement="left" title="Resend Fax"><span class="glyphicon glyphicon-share-alt"></span></a> <a data-toggle="modal"  data-target="#modalConfirmEmail" class="btn btn-primary btn-xs emailIco" data-toggle="tooltip" data-placement="left" title="Send as Email Attachment"><span class="glyphicon glyphicon-envelope"></span></a>  <a data-toggle="modal"  data-target="#modalConfirmDelete" class="btn btn-danger btn-xs deleteIco" data-toggle="tooltip" data-placement="left" title="Delete Fax"><span class="glyphicon glyphicon-remove"></span></a>'
                    }
                ]

            }
    );

</script>
<script type="text/javascript">

    $(document).ready(function () {

        //////////////////////////////////////////////////////////////////////////
        //////////     DataTables     //////////
        //////////////////////////////////////////////////////////////////////


        // DT base rendering ( landing/default Dashboard page )
        DT_FaxList.draw();


        //////////////////////////////////////////////////////////////////////////
        //////////     EXECUTIONS     //////////
        //////////////////////////////////////////////////////////////////////
        var dtFaxId = '';
        var deletedFax = 0;
        var resendFax = 0;
        // Delete Fax Dialogue
        $('table#TblFaxList_id').on( 'click', '.deleteIco', function () {
            deletedFax = 0 ;
            $("#delete-notification").html('');
            $("#delete-result").html('');
            $("#delete-waiting").html('');
            // enable delete button
            $("#cfmDeleteBtn").prop("disabled", false);
            // set default value to Cancel
            $("#btnModalCancelDelete").val("Cancel");
            var data = DT_FaxList.row( $(this).parents('tr') ).data();
            dtFaxId = data['Id'];

            $('#delete-notification').append('<p>Fax Id:  <strong>' + dtFaxId + '</strong></p>');

        } );
        // Proceed Delete
        $('#modalConfirmDelete').on('click','#cfmDeleteBtn', function (e) {
        // ajax start
        $(document).ajaxStart(function(){
            $("#delete-waiting").html('');
            $('#delete-waiting').append('<p>Deleting..please wait.</p>');
        });
        // ajax complete
        $(document).ajaxComplete(function(){
            $("#delete-waiting").html('');
            $('#delete-waiting').append('<p>Completed ! Close this window to refresh fax list. </p>');
        });
            $.ajax
                    ({
                        type: "POST",
                        url: "_deleteFaxFunction.php",
                        data: { dtFaxId: dtFaxId },
                        success: function(data)
                        {
                            $('#delete-result').append('<p>Response Status : <strong> ' + data + ' </strong></p>');
                            // disable delete button
                            $("#cfmDeleteBtn").prop("disabled", true);

                            // clear the button text and set to Close
                            $("#btnModalCancelDelete").text("Close");
                          //  $(this).button('Close')
                            deletedFax = 1;
                        }
                    });
        }); // end confirm-delete

        // Hide Delete Dialogue - check whether need to refresh page list or not
        $('#btnModalCancelDelete').on( 'click', function () {
            if(deletedFax== 1){ // delete fax so need to refresh list
                location.reload(true);
            }
        } );

        // Fax Details Dialogue
        $('table#TblFaxList_id').on( 'click', '.viewIco', function () {
            var data = DT_FaxList.row( $(this).parents('tr') ).data();
            dtFaxId = data['Id'];
            // faxid
            $("#modal-details-faxid").html('');
            $('#modal-details-faxid').append('<p class="form-control-static">' + data['Id'] + '</p>');
            // date created
            $("#modal-details-CreatedDate").html('');
            $('#modal-details-CreatedDate').append('<p class="form-control-static">' + data['CreatedDate'] + '</p>');
            // SenderName
            $("#modal-details-SenderName").html('');
            $('#modal-details-SenderName').append('<p class="form-control-static">' + data['SenderName'] + '</p>');
            // IsRead
            $("#modal-details-IsRead").html('');
            $('#modal-details-IsRead').append('<p class="form-control-static">' + data['IsRead'] + '</p>');
            // Subject
            $("#modal-details-Subject").html('');
            $('#modal-details-Subject').append('<p class="form-control-static"><u>' + data['Subject'] + '</u></p>');
            // RecpName
            $("#modal-details-RecpName").html('');
            $('#modal-details-RecpName').append('<p class="form-control-static">' + data['RecpName'] + '</p>');
            // RecpAddress
            $("#modal-details-RecpAddress").html('');
            $('#modal-details-RecpAddress').append('<p class="form-control-static">' + data['RecpAddress'] + '</p>');
            // MsgStatus
            $("#modal-details-MsgStatus").html('');
            $('#modal-details-MsgStatus').append('<p class="form-control-static">' + data['MsgStatus'] + '</p>');
            // PageCount
            $("#modal-details-PageCount").html('');
            $('#modal-details-PageCount').append('<p class="form-control-static">' + data['PageCount'] + '</p>');


        } );

        // Download Fax
        $('table#TblFaxList_id').on( 'click', '.downloadIco', function () {
            $(".sdownload").html('');
            $(document).ajaxStart(function(){

                $("#download-notification").html('');
                $('#download-notification').append('<p>Requesting..please wait.</p>');
            });
            $(document).ajaxComplete(function(){
                $("#download-notification").html('');
                $('#download-notification').append('<p>Finished ! Click link below to download this fax. </p>');
            });
            var data = DT_FaxList.row( $(this).parents('tr') ).data();
            dtFaxId = data['Id'];

            $.ajax
                    ({
                        type: "POST",
                        url: "_downloadFaxFunction.php",
                        data: { dtFaxId: dtFaxId },
                        success: function(data)
                        {
                            $('.sdownload').append('<p>Dowload Fax : <strong> <a href="' + data + '" target="_blank"> ' + data + ' </a></strong></p>');
                        }
                    });

        });

        // Resend Fax Dialogue
        $('table#TblFaxList_id').on( 'click', '.resendIco', function () {
            resendFax = 0 ;
            $("#resend-notification").html('');
            $("#resend-result").html('');
            $("#resend-waiting").html('');
            // enable resend button
            $("#cfmResendBtn").prop("disabled", false);
            // set default value to Cancel
            $("#btnModalCancelResend").val("Cancel");
            var data = DT_FaxList.row( $(this).parents('tr') ).data();
            dtFaxId = data['Id'];
            dtFaxRecipientName = data['RecpName'];
            dtFaxSubject = data['Subject'];



            $('#resend-notification').append('<p>Fax Id:  <strong>' + dtFaxId + '</strong></p><br><p>Recipient Name:  <strong>' + dtFaxRecipientName + '</strong></p><br><p>Fax Subject:  <strong>' + dtFaxSubject + '</strong></p>');

        } );

        // Proceed Resend
        $('#modalConfirmResend').on('click','#cfmResendBtn', function (e) {
            // ajax start
            $(document).ajaxStart(function(){
                $("#resend-waiting").html('');
                $('#resend-waiting').append('<p>Resending fax..please wait.</p>');
            });
            // ajax complete
            $(document).ajaxComplete(function(){
                $("#resend-waiting").html('');
                $('#resend-waiting').append('<p>API Response : <br></p>');
            });
            $.ajax
            ({
                type: "POST",
                url: "_resendFaxFunction.php",
                data: { dtFaxId: dtFaxId },
                success: function(data)
                {
                    $('#resend-result').append('<p style="color: dodgerblue"><strong> ' + data + ' </strong></p>');
                    // disable delete button
                    $("#cfmResendBtn").prop("disabled", true);

                    // clear the button text and set to Close
                    $("#btnModalCancelResend").text("Close");

                    resendFax = 1;
                }
            });
        }); // end confirm-resend

        // Hide Resend Dialogue - check whether need to refresh page list or not
        $('#btnModalCancelResend').on( 'click', function () {
            if(resendFax== 1){ // resend fax so need to refresh list
                location.reload(true);
            }
        } );

        // Email Fax Dialogue
        $('table#TblFaxList_id').on( 'click', '.emailIco', function () {
            emailFax = 0 ;
            $("#email-notification").html('');
            $("#email-result").html('');
            $("#email-waiting").html('');
            // enable resend button
            $("#cfmEmailBtn").prop("disabled", false);
            // set default value to Cancel
            $("#btnModalCancelEmail").val("Cancel");
            var data = DT_FaxList.row( $(this).parents('tr') ).data();
            dtFaxId = data['Id'];
            dtFaxSubject = data['Subject'];

            $('#email-notification').append('<p>Fax Id:  <strong>' + dtFaxId + '</strong></p><br><p>Subject:  <strong>' + dtFaxSubject + '</strong></p>');

        } );

        // Validate blank fields
        $('#modalConfirmEmail').on('click','#cfmEmailBtn', function (e) {
            // ajax start
          // var fmFaxId = $('#inputRecipient1Name').val());
            if (!$('#inputRecipient1Name').val() || !$('#Recipient1FaxNo').val() ) {
                $("#email-waiting").html('');
                $('#email-waiting').append('<p style="color: red">You must fill-in Recipient\'s 1 info.</p>');
               // alert('blank');
            }else{
               var faxRecipientEmailList = $('form#emailFaxForm').serializeJSON();
               // alert(faxRecipientEmailList);
                // ajax start
                $(document).ajaxStart(function(){
                    $("#email-waiting").html('');
                    $('#email-waiting').append('<p>Sending fax as email attachment..please wait.</p>');
                });
                // ajax complete
                $(document).ajaxComplete(function(){
                    $("#email-waiting").html('');
                    $('#email-waiting').append('<p>API Response <br></p>');
                });
                $.ajax
                ({
                    type: "POST",
                    url: "_emailFaxFunction.php",
                    data: { dtFaxId: dtFaxId, faxRecipientEmailList: faxRecipientEmailList  },
                    success: function(data)
                    {
                        $('#email-result').append('<p>Response Status : <strong> ' + data + ' </strong></p>');
                        // disable delete button
                        $("#cfmEmailBtn").prop("disabled", true);

                        // clear the button text and set to Close
                        $("#btnModalCancelEmail").text("Close");

                        emailFax = 1;
                    }
                });
            }

            e.preventDefault();
        }); // end confirm-resend


        // Hide Email Dialogue - check whether need to refresh page list or not
        $('#btnModalCancelEmail').on( 'click', function () {
            if(emailFax== 1){ // email fax so need to refresh list
                location.reload(true);
            }
        } );

    }); // ./end doc ready


</script>
