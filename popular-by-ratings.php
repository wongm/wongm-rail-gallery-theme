<?php
$key = 'ratings';
$popularImageText['key'] = $key;
setCustomPhotostream($popularImageText[$key]['where'], "", $popularImageText[$key]['order']);

require_once('uploads-base.php');
?>