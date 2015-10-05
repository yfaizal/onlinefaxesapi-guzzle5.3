# onlinefaxesapi-guzzle5.3
Sample application for OnlineFaxes API using Guzzle5.3

This is a basic/simple PHP application using Guzzle5.3 to interact with <a href="https://onlinefaxes.readme.io/v2.0" target="_blank">OnlineFaxes API</a>.
You can download and modify it to suit with your needs.

#### Screenshot
![dashboard image](http://syngular.pw/sample/dashboard.jpg)
![send fax form](http://syngular.pw/sample/send-fax.jpg)

#### Features included :-
* Auto-checking token expiry before every request.
* Re-request for a new token and save it into database ( sample sql included. )
* Use PHP Session Upload Progress to hold Posting to API until finished uploading/attaching big file(s).

#### Features NOT included :-
* Form Validation ( Form for sending Fax )

#### Live Demo
Click <a href="http://syngular.pw/sample" target="_blank">here</a> to see a live demo.

#### Built with and tested on :-
* <a href="https://github.com/guzzle/guzzle/tree/5.3" target="_blank">Guzzle5.3</a>.
* <a href="https://www.debian.org/releases/wheezy/" target="_blank">Debian 7</a>.
* <a href="http://httpd.apache.org/docs/2.2/" target="_blank">Apache 2.2.22</a>.
* <a href="http://php.net/downloads.php#v5.4.0" target="_blank">PHP 5.4.44</a>.
* <a href="http://datatables.net/" target="_blank">DataTables</a> for fax listing.
* <a href="http://getbootstrap.com/" target="_blank">Twitter Bootstrap</a> for theme and layout.

#### Implement all but one API from onlinefaxes.readme.io :-
* Send Fax-Complex Model
* Get Access Token
* Get Fax Status
* Download Fax
* Delete Fax
* Get Fax Detail
* Get Fax List

Note: Did not implement 'Send Fax-Simple Model' because personally I think the 'Complex Model' example already cover it all. 

#### Official Documentation
Please visit <a href="https://onlinefaxes.readme.io/v2.0" target="_blank">OnlineFaxes API</a> for all the API lists and it's documentation.

#### Version
* 1.0 - October 1st. 2015

#### Directory structure

This is the directory structure you will end up with following the instructions in the Installation Guide.

    |-- sample
    |   |-- uploads   
    |   |-- vendor
    |       |-- guzzlehttp
    |       |-- ... ( others libs/dependencies )    

* `/sample` - application folder that contained all the php files.
* `/sample/uploads` - for attachment/upload files before being pick-up by Guzzle PostFile() to send fax.
* `/sample/vendor` - Mainly for Guzzle and it's dependencies - through composer.

#### TODO
* Dynamically add/remove recipient.
* Dynamically add/remove file.
