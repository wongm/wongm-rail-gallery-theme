<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$key = 'this-week';
$popularImageText['key'] = $key;
setCustomPhotostream($popularImageText[$key]['where'], "", $popularImageText[$key]['order']);
require_once('uploads-base.php');
?>