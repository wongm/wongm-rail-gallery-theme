<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$popularImageText['key'] = 'resize';

$where = "((i.height = 1024 AND i.width != 683) OR (i.width = 1024 AND i.height != 683) OR (i.height = 1920 AND i.width != 1280) OR (i.width = 1920 AND i.height != 1280)) AND a.folder NOT LIKE 'wagons%'";
setCustomPhotostream($where);
require_once('uploads-base.php');
?>