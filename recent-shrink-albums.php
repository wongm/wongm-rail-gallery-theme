<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$popularImageText['key'] = 'shrink-albums';
setCustomPhotostream(IMAGE_NEEDING_SHRINK_SQL, "i.albumid", "i.albumid, i.date DESC");

require_once('uploads-base.php');
?>