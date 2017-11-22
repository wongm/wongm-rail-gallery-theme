<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$key = 'trams';
$popularImageText['key'] = $key;
$popularImageText['trams']['url'] = TRAMS_UPDATES_URL_PATH;
$popularImageText['trams']['title'] = 'Recent uploads - Trams';
$popularImageText['trams']['text'] = 'Recent tram uploads';
$popularImageText['trams']['subtext'] = 'Trams, tram stops and tramway infrastructure';
$popularImageText['trams']['type'] = 'recent';
$popularImageText['trams']['where'] = "folder LIKE '%tram%' OR folder LIKE '%light-rail%'";

setCustomPhotostream($popularImageText[$key]['where']);

require_once('uploads-base.php');
?>