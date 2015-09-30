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
<table class="headbar">
    <tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
    <?=$pageBreadCrumb?>
    </td><td id="righthead"><? printSearchForm();?></td></tr>
</table>
<?

$now = time();
$melbournetimezone = new DateTimeZone('Australia/Melbourne');
$timestamp = new DateTime();
$timestamp->setTimestamp($now);
$timestamp->setTimezone($melbournetimezone);

?>
<div class="topbar">
    <h2>On this day, <?php echo $timestamp->format('F d') ?></h2>
</div>
<?

foreach (array(1, 5, 10) AS $year)
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
        echo "<div class=\"pastyears\"><h3>$year $suffix ago</h3>\n";
        echo "<p>$photocount photos found - <a href=\"/page/archive/$dayLink\">View more...</a>$extraText</p></div>";
?>
<table class="centeredTable">
    <tr class="trio">
<?php
        $count = 0;

        // draw top items
        while (next_photostream_image() && (++$count <= $photosPerItem ))
        {
            drawWongmImageCell('uploads');
        }
        
        while (++$count <= $photosPerItem )
        {
            echo "<td class=\"image\"></td>";
        }
    }
?>
    </tr>
</table>
<?
}

include_once('footer.php'); 
?>