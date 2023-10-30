<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$key = 'bus';
$popularImageText['key'] = $key;
setOption('photostream_images_per_page', $popularImageText[$key]['maxcount'], false);
setCustomPhotostream($popularImageText[$key]['where'], "", $popularImageText[$key]['order']);

require_once('uploads-base.php');
?>