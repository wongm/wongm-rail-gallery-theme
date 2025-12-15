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
	<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo;
    <?php echo $pageBreadCrumb; ?>
	</span><span id="righthead"><?php printSearchForm(); ?></span>
</div>
<?php

$date = "";
$timestamp = time();
if (isset($_GET['date']))
{
	$date = $_GET['date'];
    $timestamp = strtotime($date);
}

$melbournetimezone = new DateTimeZone('Australia/Melbourne');
$melbouneTimestamp = new DateTime();
$melbouneTimestamp->setTimestamp($timestamp);
$melbouneTimestamp->setTimezone($melbournetimezone);

?>
<div class="topbar">
    <h2>On this day, <?php echo $melbouneTimestamp->format('F d') ?></h2>
</div>
<?php

if (isset($_GET['wongm']))
{
	$summaryForCurrentDay = getSummaryForCurrentDay($date);
	$displayedImage = $summaryForCurrentDay->imagePageUrl;
?>
<div class="album recentuploads">
    <div class="albumthumb">
        <a href="<?php echo $summaryForCurrentDay->imagePageUrl ; ?>" title="On this day"><img src="<?php echo $summaryForCurrentDay->imageUrl; ?>" /></a>
    </div>
    <div class="summarydesc">
        <p><span class="recent"><?php echo $summaryForCurrentDay->yearsAgo; ?></span> - <?php echo $summaryForCurrentDay->desc; ?></p>
    </div>
</div>
<?php
}



foreach (array(1, 2, 5, 10, 15, 20) AS $year)
{
    if ($year == 1)
    {
        $suffix = "year";
    }
    else
    {
        $suffix = "years";
    }

    $candidateTimestamp = new DateTime();
    $candidateTimestamp->setTimestamp($timestamp);
    $candidateTimestamp->setTimezone($melbournetimezone);
    $candidateTimestamp->sub(new DateInterval('P' . $year . 'Y'));
    $dayLink = $candidateTimestamp->format('Y-m-d');

    // run the query
    setCustomPhotostream("i.date >= '$dayLink' AND i.date < '$dayLink' + INTERVAL 1 DAY", "", "i.hitcounter DESC");

    // validate we have photos to show
    $photocount = getNumPhotostreamImages();

    if ($photocount > 0)
    {
		global $_zp_current_DailySummaryItem;
		$_zp_current_DailySummaryItem = new DailySummaryItem($dayLink);
        echo "<div class=\"pastyears\"><h3>$year $suffix ago</h3>\n";
        echo "<p>$photocount photos " . getDailySummaryDescInternal() . " - <a href=\"/page/archive/$dayLink\">View more...</a></p></div>";
?>
<div id="imagewrapper">
    <div id="images">
<?php
        $count = 0;

        // draw top items
        while (next_photostream_image() && (++$count <= $photosPerItem ))
        {
			// if image already displayed at top of page, skip over it
			if ($displayedImage == getImageURL())
			{
				$count--;
				continue;
			}
			
            drawWongmImageCell('uploads');
        }
?>
    </div>
</div>
<?php
    }
}

include_once('footer.php'); 
?>