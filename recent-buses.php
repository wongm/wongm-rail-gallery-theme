<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$key = 'buses';
$popularImageText['key'] = $key;
$popularImageText['buses']['url'] = BUSES_UPDATES_URL_PATH;
$popularImageText['buses']['title'] = 'Recent uploads - Buses';
$popularImageText['buses']['text'] = 'Recent bus uploads';
$popularImageText['buses']['subtext'] = 'Buses, bus stops and bus depots';
$popularImageText['buses']['type'] = 'recent';
$popularImageText['buses']['where'] = "folder LIKE '%bus%' OR folder LIKE 'road-coaches'";

setCustomPhotostream($popularImageText[$key]['where']);

require_once('uploads-base.php');
?>