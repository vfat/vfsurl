<?php
header("Access-Control-Allow-Origin: *");
date_default_timezone_set('asia/jakarta'); 



$vf=require('lib/base.php');

/*
=============
GLOBAL CONFIG
=============
*/
$vf->config('config/config.ini');

/*
=============
GLOBAL CONFIG
=============
*/
$vf->config('route/route.ini');


$vf->run();

?>