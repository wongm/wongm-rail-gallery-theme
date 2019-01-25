<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$popularImageText['key'] = 'uncaptioned-albums';
setCustomPhotostream(UNCAPTIONED_IMAGE_REGEX, "i.albumid");

require_once('uploads-base.php');
?>