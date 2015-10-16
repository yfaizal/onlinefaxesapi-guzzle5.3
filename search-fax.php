<!-- HEADER
================================================== -->
<?php
require("_header.php");
require("_check_token.php");
?>

<div class="container-fluid">

    <?php
        $setDashboardActiveLink = 'mnusearch';
        require("_dashboard-menu.php");
        // check redirect action
         // echo $_POST['faxid'].'-'.$_POST['action'];

    ?>

    <?php
    use GuzzleHttp\Client;
    use GuzzleHttp\Query;

    $client = new Client();

    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
       // echo $_POST['faxid'].'-'.$_POST['action'].'='.$_POST['search-type'];
        $postFaxId = $_POST['faxid'];
        $uriSearch = $_POST['uri-search'];

            $request = $client->createRequest('POST', $configs['base_url'].'/fax/async/'.$uriSearch, [
                'headers' => $configs['headers'],
                'query' => ['faxId' => $postFaxId],
               // 'verify' => true
            ]);
            // var_dump($request->getQuery());exit;
            $response = $client->send($request);
           // var_dump($response->getBody());
        ?>
        <script>
            // assign search faxid to the input box
            document.getElementById('inputFaxId').value = '<?php echo $postFaxId;?>';
        </script>

    <?php }else{
       // not a POST method;
    }
    ?>

        <h4>Search Fax Id : <?php echo $postFaxId;?> | Search Type : <?php echo $uriSearch;?></h4>

    <p>Returned Response :</p>
    <pre><?php var_dump($json);?></pre>


</div><!-- /.container -->


<!-- FOOTER
================================================== -->
<?php require("_footer.php"); ?>
