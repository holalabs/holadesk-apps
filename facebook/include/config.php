<?php
/*
Start customizations
*/

define('FACEBOOK_APP_ID', '392355624148290');
define('FACEBOOK_SECRET', '9b50e5c2e0616119b524e397a1935b17');

$base_url = 'https://desk.holalabs.com'; //ex: http://yougapi.com
$app_folder = '/apps/facebook'; //ex: /products/mobile/facebook_mobile

$GLOBALS['google_analytics'] = 'UA-25177280-1'; //UA-123456789

/*
Start customizations
*/

//The next lines are not to be changed
$GLOBALS['path_app'] = $app_folder;
$GLOBALS['app_url'] = $base_url.$app_folder;
$GLOBALS['ajax_url'] = $GLOBALS['app_url'].'/include/fb/';
?>
