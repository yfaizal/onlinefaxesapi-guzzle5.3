<!-- HEADER
================================================== -->
<?php
require("_header.php");
require("_check_token.php");
?>

<div class="container-fluid">

    <?php
    require("_dashboard-menu.php");
    ?>

    <div class="row">
        <div class="col-sm-8 blog-main">

            <div class="blog-post">
        <?php
        use GuzzleHttp\Client;
        use CommerceGuys\Guzzle\Oauth2\GrantType\RefreshToken;
        use CommerceGuys\Guzzle\Oauth2\GrantType\PasswordCredentials;
        use CommerceGuys\Guzzle\Oauth2\Oauth2Subscriber;

         if($_SERVER['REQUEST_METHOD'] == "POST"){

             // request a new token
             $base_url = 'https://api.onlinefaxes.com/v2/';
             $oauth2Client = new Client(['base_url' => $base_url]);

             $config = [
                 'username' => '',
                 'password' => '',
                 'client_id' => $configs['client_info']['client_id'],
                 'client_secret' => $configs['client_info']['client_secret'],
                 'grant_type' => $configs['client_info']['grant_type'],
                 'scope' => null,
             ];

             $accessToken = new CommerceGuys\Guzzle\Oauth2\GrantType\ClientCredentials($oauth2Client, $config);
             $oauth2 = new Oauth2Subscriber($accessToken);
             $response = $oauth2->getAccessToken();


             // Use $oauth2->getAccessToken(); and $oauth2->getRefreshToken() to get tokens
             // that can be persisted for subsequent requests.


             // update a new token into database
             $conn = new mysqli($configs['db_conn']['hostname'], $configs['db_conn']['username'], $configs['db_conn']['password'], $configs['db_conn']['database']);
            // Check connection
             if ($conn->connect_error) {
                 die("Connection failed: " . $conn->connect_error);
             }

             $sql = "UPDATE `onlinefaxes_token` SET token='" . $response->getToken() . "' WHERE id=1";

             if ($conn->query($sql) === TRUE) {
                /* Successful so redirect to dashboard/index */
                 echo '<script type="text/javascript">
                           window.location = "index.php"
                      </script>';
             } else {
                 echo "Error updating token: " . $conn->error; exit;
             }
             // close the connection
             $conn->close();

/*             echo '<pre /><code />';
             echo 'Token :'.$response->getToken();echo '<br>';
             echo 'Type :'.$response->getType();echo '<br>';
             echo 'Expire Date :'.$response->getExpires()->format('Y-m-d H:i:s');echo '<br>';
             echo 'Time Zone :'.$response->getExpires()->getTimezone()->getName();
             echo '</code></pre>';*/
            ?>
<!--             <div class="col-md-8 col-md-offset-2">
                 <form method="get">
                     <button class="btn btn-success btn-lg btn-block" formaction="index.php" type="submit" name="btn_token" value="">Token successfully update! You can proceed to any API operation.</button>
                 </form>
             </div>-->

       <?php  }else{
        ?>

        <div class="col-md-8 col-md-offset-4">
            <form method="post">
                <button class="btn btn-info btn-lg btn-block" formaction="get-token.php" type="submit" name="btn_token" value="">Get a new token</button>
            </form>
        </div>

        <?php } ?>
            </div>

        </div>
    </div>




    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            &nbsp;
        </div>
    </div>
</div><!-- /.container -->


<!-- FOOTER
================================================== -->
<?php require("_footer.php"); ?>

