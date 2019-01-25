<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$key = 'trams';
$popularImageText['key'] = $key;
setCustomPhotostream($popularImageText[$key]['where']);

require_once('uploads-base.php');
?>