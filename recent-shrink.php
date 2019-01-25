<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$popularImageText['key'] = 'shrink';
setCustomPhotostream(IMAGE_NEEDING_SHRINK_SQL);

require_once('uploads-base.php');
?>