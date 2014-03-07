<?php
include('../include/webzone.php');
unset($_SESSION['user_id']);
header('Location: '.$GLOBALS['app_url']);
?>