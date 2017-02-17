<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$popularImageText['key'] = 'duplicates';
$sqlJoin = "INNER JOIN (SELECT inside.filename FROM zen_images inside GROUP BY inside.filename HAVING ( COUNT(inside.filename) > 1 )) AS dupes ON i.filename = dupes.filename";
setCustomPhotostream("", "", "", $sqlJoin);
require_once('uploads-base.php');
?>