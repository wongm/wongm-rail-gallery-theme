<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$popularImageText['key'] = 'duplicates';

$where = "1=1) GROUP BY i.filename HAVING  ( COUNT(i.filename) > 1 ";
setCustomPhotostream($where);
require_once('uploads-base.php');
?>