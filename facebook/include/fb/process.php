<?php
include_once("include/webzone.php");

//Listeners
if(@$_GET['q']=='facebookListener') include("listeners/ajax_facebookListener.php");

//Views
else if(@$_GET['q']=='facebookView') include("views/ajax_facebookView.php");

else echo 'Hello world.';

?>