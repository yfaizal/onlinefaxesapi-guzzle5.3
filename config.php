<?php
// For demo purpose only.Must be put outside public folder or save into database

//get token from database
$conn = new mysqli("localhost","my_user","my_password","my_db");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 $qToken = $conn->query("SELECT token FROM onlinefaxes_token WHERE id = 1")->fetch_object()->token;
//  print $qToken; //output token
// close the connection
$conn->close();

return array(
    'headers' => array('Content-Type' => 'application/x-www-form-urlencoded',
    'Authorization' => 'ofx  '.$qToken),
    'base_url' => 'https://api.onlinefaxes.com/v2',
    'db_conn' => array(
        'username' => 'my_user',
        'password' => 'my_password',
        'database' => 'my_db',
        'hostname' => 'localhost'
    ),
    'client_info' => array(
        'username' => '',
        'password' => '',
        'client_id' => 'YOUR_CLIENT_ID',
        'client_secret' => 'YOUR_CLIENT_SECRET',
        'grant_type' => 'client_credentials',
        'scope' => null,
       // 'verify' => 'cert.pem'
    ),
    'file_upload_valid_formats' => array("html",
        "htm",
        "mhtml",
        "xhtml",
        "pdf",
        "xps",
        "ppt",
        "pptx",
        "rtf",
        "odt",
        "bmp",
        "tif",
        "xps",
        "xml",
        "txt",
        "pcl",
        "png",
        "jpg",
        "gif",
        "doc",
        "docx",
        "xls",
        "xlsx"
    ),
    'max_file_size' => 1024*20480, //20M - 1024*20480,
    'filePath' => 'uploads/'
);
