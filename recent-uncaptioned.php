<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$popularImageText['key'] = 'uncaptioned';

$where = UNCAPTIONED_IMAGE_REGEX;
setCustomPhotostream($where);
require_once('uploads-base.php');
?>