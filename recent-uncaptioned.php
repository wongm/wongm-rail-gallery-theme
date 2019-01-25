<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$popularImageText['key'] = 'uncaptioned';
setCustomPhotostream(UNCAPTIONED_IMAGE_REGEX);

require_once('uploads-base.php');
?>