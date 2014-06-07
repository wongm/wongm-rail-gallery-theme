<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$where = "(i.height != 1024 AND i.width != 683) OR (i.width != 1024 AND i.height != 683)";
setCustomPhotostream($where);
require_once('uploads-base.php');
?>