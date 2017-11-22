<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$popularImageText['key'] = 'uncaptioned-albums';

$where = UNCAPTIONED_IMAGE_REGEX;
$groupBy = "i.albumid";
setCustomPhotostream($where, $groupBy);
require_once('uploads-base.php');
?>