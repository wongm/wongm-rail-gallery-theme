<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

/*
 * Shows a list of different 'on this day' photos
 * - last year
 * - five years
 * - ten years
 *
 */ 
 
$photosPerItem = 3;

$pageTitle = ' - On this day';
include_once('header.php');
$pageBreadCrumb = 'On this day';
?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
    <?=$pageBreadCrumb?>
	</span><span id="righthead"><?printSearchForm();?></span>
</div>
<?

$now = time();
if (isset($_GET['date']))
{
    $now = strtotime($_GET['date']);
}

$melbournetimezone = new DateTimeZone('Australia/Melbourne');
$timestamp = new DateTime();
$timestamp->setTimestamp($now);
$timestamp->setTimezone($melbournetimezone);

?>
<div class="topbar">
    <h2>On this day, <?php echo $timestamp->format('F d') ?></h2>
</div>
<?

foreach (array(1, 2, 5, 10, 15) AS $year)
{
    if ($year == 1)
    {
        $suffix = "year";
    }
    else
    {
        $suffix = "years";
    }

    $timestamp = new DateTime();
    $timestamp->setTimestamp($now);
    $timestamp->setTimezone($melbournetimezone);
    $timestamp->sub(new DateInterval('P' . $year . 'Y'));
    $dayLink = $timestamp->format('Y-m-d');

    // run the query
    setCustomPhotostream("i.date >= '$dayLink' AND i.date < '$dayLink' + INTERVAL 1 DAY", "", "i.hitcounter DESC");

    // validate we have photos to show
    $photocount = getNumPhotostreamImages();

    if ($photocount > 0)
    {
		$_zp_current_DailySummaryItem = new DailySummaryItem($dayLink);		
        echo "<div class=\"pastyears\"><h3>$year $suffix ago</h3>\n";
        echo "<p>$photocount photos " . getDailySummaryDescInternal() . " - <a href=\"/page/archive/$dayLink\">View more...</a>$extraText</p></div>";
?>
<div id="imagewrapper">
    <div id="images">
<?php
        $count = 0;

        // draw top items
        while (next_photostream_image() && (++$count <= $photosPerItem ))
        {
            drawWongmImageCell('uploads');
        }
?>
    </div>
</div>
<?
    }
}

include_once('footer.php'); 
?>