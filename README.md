# onlinefaxesapi-guzzle5.3
Sample application for OnlineFaxes API using Guzzle5.3

This is a basic/simple PHP application using Guzzle5.3 to interact with <a href="https://onlinefaxes.readme.io/v2.0" target="_blank">OnlineFaxes API</a>.
You can download and modify it to suit with your needs.

#### Features included :-
* Auto-checking token expiry before every request.
* Re-request for a new token and save into database ( sample sql included. )

#### Features NOT included :-
* Form Validation ( Form for sending Fax )

#### Live Demo
Click <a href="http://syngular.pw/onlinefaxes" target="_blank">here</a> to see a live demo.

#### Built with and tested on :-
* <a href="https://github.com/guzzle/guzzle/tree/5.3" target="_blank">Guzzle5.3</a>.
* <a href="https://www.debian.org/releases/wheezy/" target="_blank">Debian 7</a>.
* <a href="http://httpd.apache.org/docs/2.2/" target="_blank">Apache 2.2.22</a>.
* <a href="http://php.net/downloads.php#v5.4.0" target="_blank">PHP 5.4.44</a>.
* <a href="http://datatables.net/" target="_blank">DataTables</a> for fax listing.
* <a href="http://getbootstrap.com/" target="_blank">Twitter Bootstrap</a> for theme and layout.

#### Official Documentation
Please visit <a href="https://onlinefaxes.readme.io/v2.0" target="_blank">OnlineFaxes API</a> for all the API lists and it's documentation.

#### Version
Click <a href="http://syngular.pw/onlinefaxes" target="_blank">here</a> to see a live demo.

# Directory structure

This is the directory structure you will end up with following the instructions in the Installation Guide.

    |-- src
    |   |-- vendor
    |       |-- guzzlehttp


* `/src` - all the php files.
* `/src/vendor` - Guzzle and other dependencies libraries.
