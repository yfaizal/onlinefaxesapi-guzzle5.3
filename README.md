# onlinefaxesapi-guzzle5.3
Sample application for OnlineFaxes API using Guzzle5.3

This is a basic/simple PHP application using Guzzle5.3 to interact with <a href="https://onlinefaxes.readme.io/v2.0" target="_blank">OnlineFaxes API</a>.
You can download and modify it to suit with your needs.

#### Screenshot
<img src="http://syngular.pw/sample/dashboard.jpg" alt="Dashboard Screenshot" width="600"/>


<img src="http://syngular.pw/sample/send-fax.jpg" alt="Send Fax Screenshot" width="600"/>

#### Features included :-
* Auto-checking token expiry before every request.
* Re-request for a new token and save it into database ( sample sql included. )
* Use PHP Session Upload Progress to hold Posting to API until finished uploading/attaching big file(s).
* Basic server-side validation to check some blank fields (send fax form).
* Use OAuth2 plugin by <a href="https://github.com/commerceguys/guzzle-oauth2-plugin" target="_blank">Commerce Guys</a>.

#### Live Demo
Click <a href="http://apidemo.onlinefaxes.com" target="_blank">here</a> to see a live demo.

#### Built with and tested on :-
* <a href="https://github.com/guzzle/guzzle/tree/5.3" target="_blank">Guzzle5.3</a>.
* <a href="https://www.debian.org/releases/wheezy/" target="_blank">Debian 7</a>.
* <a href="http://httpd.apache.org/docs/2.2/" target="_blank">Apache 2.2.22</a>.
* <a href="http://php.net/downloads.php#v5.4.0" target="_blank">PHP 5.4.44</a>.
* <a href="http://www.mysql.com/" target="_blank">MySql 5.5.44</a>.
* <a href="http://datatables.net/" target="_blank">DataTables</a> for fax listing.
* <a href="http://getbootstrap.com/" target="_blank">Twitter Bootstrap</a> for theme and layout.

#### Implement all APIs from onlinefaxes.readme.io :-
* Send Fax-Complex Model
* Send Fax-Simple Model
* Get Access Token
* Get Fax Status
* Download Fax
* Delete Fax
* Get Fax Detail
* Get Fax List

Note: The send fax operation is a combination of Simple and Complex Model.Please see send-fax.php file to understand the logic/flow. 

#### Official Documentation
Please visit <a href="https://onlinefaxes.readme.io/v2.0" target="_blank">OnlineFaxes API</a> for all the API lists and it's documentation.

#### Version
* 1.0 - October 1st. 2015

#### Directory structure

This is the directory structure you will end up with following the instructions in the Installation Guide.

    |-- sample
    |   * all .php files
    |   |-- uploads
    |   |-- scripts
    |   |-- css
    |   |-- sql
    |   |-- vendor
    |       |-- guzzlehttp
    |       |-- ... ( others libs/dependencies )    

* `/sample` - main folder that also contained all the php files.
* `/sample/config.php` - configuration file for base url,upload file settings,your client_id & secret_id settings.Set this first before running this sample application.
* `/sample/uploads` - for attachment/upload files before being pick-up by Guzzle PostFile() to send fax.
* `/sample/vendor` - Mainly for Guzzle and it's dependencies - through composer.
* `/sample/scripts` - javascripts files.
* `/sample/css` - css file for additional style.
* `/sample/sql` - sql file for token's table.You must setup this database/table because the application need this to check,request and update token.

#### Installation Guide :-
* Download the source code.
* Since it's already included composer.phar & composer.json, just run ```composer.phar update``` to get Guzzle5 and OAuth2 plugin.The ```vendor/autoload.php``` have been included in ```_header.php``` file.
* Create a database and import /sql/token_table.sql
* Set everything in config.php - db conn, api client, file upload settings etc.
* Run the sample application.

Important ! : To test/run this sample application, you MUST have an account with OnlineFaxes.com and then create fax application as mentioned <a href="https://onlinefaxes.readme.io/docs/getting-started-1" target="_blank">here</a>.You still can test this application by subscribing to their <a href="http://www.onlinefaxes.com/Pricing#pricing" target="_blank">Free Trial</a> package.

#### TODO ( Next Version )
* Dynamically add/remove recipient.
* Dynamically add/remove file.
