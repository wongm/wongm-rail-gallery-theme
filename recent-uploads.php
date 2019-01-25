<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$popularImageText['key'] = 'uploads';
setCustomPhotostream(EXCLUDED_WAGONS_SQL);

require_once('uploads-base.php');
?>