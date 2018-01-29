<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$key = 'this-year';
$popularImageText['key'] = $key;
$popularImageText['this-year']['url'] = THIS_year_URL_PATH;
$popularImageText['this-year']['title'] = 'Popular photos - Most viewed this year';
$popularImageText['this-year']['text'] = 'Most viewed this year';
$popularImageText['this-year']['type'] = 'popular';
$popularImageText['this-year']['order'] = "i.hitcounter DESC";

$yearAgoTime = strtotime("-1 year", time());
$yearAgoDate = date("Y-m-d", $yearAgoTime);

$popularImageText['this-year']['where'] = "i.date >= '$yearAgoDate' AND i.hitcounter > " . getOption('popular_threshold_hitcounter');

setCustomPhotostream($popularImageText[$key]['where'], "", $popularImageText[$key]['order']);

require_once('uploads-base.php');
?>