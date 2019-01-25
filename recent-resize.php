<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$popularImageText['key'] = 'resize';
setCustomPhotostream(IMAGE_NEEDING_RESIZE_SQL);

require_once('uploads-base.php');
?>