<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$key = 'buses';
$popularImageText['key'] = $key;
$popularImageText['buses']['url'] = BUSES_UPDATES_URL_PATH;
$popularImageText['buses']['title'] = 'Recent uploads - Buses';
$popularImageText['buses']['text'] = 'Recent bus uploads';
$popularImageText['buses']['subtext'] = 'Buses, bus stops and bus depots';
$popularImageText['buses']['type'] = 'recent';
$popularImageText['buses']['where'] = "a.id IN (SELECT `objectid` FROM zen_obj_to_tag INNER JOIN zen_tags ON zen_obj_to_tag.`tagid` = zen_tags.`id` WHERE zen_obj_to_tag.`type` = 'albums' AND zen_tags.`name` = 'buses')";

setCustomPhotostream($popularImageText[$key]['where']);

require_once('uploads-base.php');
?>