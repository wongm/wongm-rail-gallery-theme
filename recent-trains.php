<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$key = 'trains';
$popularImageText['key'] = $key;
$popularImageText['trains']['url'] = BUSES_UPDATES_URL_PATH;
$popularImageText['trains']['title'] = 'Recent uploads - Trains';
$popularImageText['trains']['text'] = 'Recent train uploads';
$popularImageText['trains']['subtext'] = 'Trains and railway infrastructure';
$popularImageText['trains']['type'] = 'recent';
$popularImageText['trains']['where'] = "folder NOT LIKE '%tram%' AND folder NOT LIKE '%light-rail%' AND folder NOT LIKE 'wagons%' AND a.id NOT IN (SELECT `objectid` FROM zen_obj_to_tag INNER JOIN zen_tags ON zen_obj_to_tag.`tagid` = zen_tags.`id` WHERE zen_obj_to_tag.`type` = 'albums' AND zen_tags.`name` = 'buses')";

setCustomPhotostream($popularImageText[$key]['where']);

require_once('uploads-base.php');
?>