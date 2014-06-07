<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$where = "i.title REGEXP '_[0-9]{4}' OR i.title REGEXP 'DSCF[0-9]{4}'";
$groupBy = "i.albumid";
setCustomPhotostream($where, $groupBy);
require_once('uploads-base.php');
?>