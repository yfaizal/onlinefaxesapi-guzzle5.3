<?php
if(strpos($_SERVER['REQUEST_URI'],'index')!== false ){
    // check dropdown folder
    if($_SERVER['QUERY_STRING'] =='' ){
        $setDashboardActiveLink = 'selectReceived';
        $setFolderId = '1001'; // received list
    }elseif($_SERVER['QUERY_STRING'] =='1001'){
        $setDashboardActiveLink = 'selectReceived';
        $setFolderId = '1001'; // received list
    }elseif($_SERVER['QUERY_STRING'] =='1002'){
        $setDashboardActiveLink = 'selectProcessing';
        $setFolderId = '1002'; // processing list
    }elseif($_SERVER['QUERY_STRING'] =='1003'){
        $setDashboardActiveLink = 'selectSent';
        $setFolderId = '1003'; // sent list
    }elseif($_SERVER['QUERY_STRING'] =='1004'){
        $setDashboardActiveLink = 'selectFailed';
        $setFolderId = '1004'; // failed list
    }elseif($_SERVER['QUERY_STRING'] =='1007'){
        $setDashboardActiveLink = 'selectDeleted';
        $setFolderId = '1007'; // deleted list
    }else{
        $setDashboardActiveLink = 'selectReceived';
        $setFolderId = '1001'; // others - other than 1 ~ 7 will set to 1001/received
    }
}elseif( (strpos($_SERVER['REQUEST_URI'],'send-fax')!== false ) || (strpos($_SERVER['REQUEST_URI'],'search-fax')!== false )  ){
        //set dropdown to 'select folder'
        $setDashboardActiveLink = 'selectOthers';
        $setFolderId = '';
}else {
    $setDashboardActiveLink = 'selectReceived';
    $setFolderId = '1001';
}
?>

    <h2 id="tables-condensed">Fax Dashboard</h2>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Fax Lists :</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <form class="navbar-form navbar-left" role="search">
                        <select  onchange="if (this.value) window.location.href=this.value" class="form-control">
                            <option value="index.php?1001" <?php if ($setDashboardActiveLink == 'selectReceived') { echo 'selected'; };?>>Received</option>
                            <option value="index.php?1002" <?php if ($setDashboardActiveLink == 'selectProcessing') { echo 'selected'; };?>>Processing</option>
                            <option value="index.php?1003" <?php if ($setDashboardActiveLink == 'selectSent') { echo 'selected'; };?>>Sent</option>
                            <option value="index.php?1004" <?php if ($setDashboardActiveLink == 'selectFailed') { echo 'selected'; };?>>Failed</option>
                            <option value="index.php?1007" <?php if ($setDashboardActiveLink == 'selectDeleted') { echo 'selected'; };?>>Deleted</option>
                            <option id="dropdownOthers" value="disabled" <?php if ($setDashboardActiveLink == 'selectOthers') { echo 'selected'; };?>>Select Folder</option>
                        </select>
                    </form>
                </ul>
                <a class="navbar-brand" href="#">Send Fax :</a>
                <p class="navbar-text"><strong><a class="text-danger" href="send-fax.php"> Fax Form</a></strong></p>


                <ul class="nav navbar-nav navbar-right">
                    <button id="token-status" type="button" class="btn <?php echo $tokenBtn;?> navbar-btn token-status"> <?php echo $tokenText;?>&nbsp;<span class="glyphicon <?php echo $tokenIcon;?>" aria-hidden="true"></span> </button>
                    <form class="navbar-form navbar-right" method="post" role="search" action="search-fax.php">
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputFaxId" name="faxid" value="" placeholder="Fax Id">
                        </div>
                        <select class="form-control" name="uri-search">
                            <option value="getfaxstatus">Fax Status</option>
                            <option value="getfaxdetail">Fax Details</option>
                        </select>
                        <button type="submit" class="btn btn-warning">Search</button>
                    </form>

                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>