<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Welcome';
$rssType = 'Gallery';
$rssTitle = 'Recent uploads';

include_once('header.php');

global $_randomImageAttempts;

$mostRecentImageData = getMostRecentImageData();
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; Home</td>
	<td><?printSearchForm();?></td></tr>
</table>
<h2 class="index">News</h2>
<table class="indexalbums">
<tr class="album">
	<td class="albumthumb">
		<a href="<?=UPDATES_URL_PATH?>" title="Recent uploads"><img src="<? echo $mostRecentImageData['thumbnailUrl']; ?>" alt="Recent uploads" /></a>
	 </td><td class="albumdesc">
		<h4><a href="<?=UPDATES_URL_PATH?>" title="Recent uploads">Recent uploads</a></h4>
		<p><? echo $mostRecentImageData['content']; ?></p>
		<ul><?php NewDailySummary(5);
        while (next_DailySummaryItem()) { ?>
        <li><p><?php echo date("F j", strtotime(getDailySummaryDate())); ?> - <?php echo getDailySummaryNumImages(); ?> new photos in <?php echo getDailySummaryAlbumNameText(); ?></p></li>
        <?php } ?>
        </ul>
	</td>
</tr>
<?

if (function_exists('next_news')) {

    $i = 0;    
    
    while (next_news() AND $i++ < getOption('wongm_news_count')): ;?>
<tr class="album">
 	<? if ($i == 1) { ?>
	<td class="albumthumb" rowspan="<?=getOption('wongm_news_count') + 1?>" valign="top"></td>
	<? } ?>
 	<td class="albumdesc">
    	<h4><?php printNewsURL(); ?></h4>
    	<p class="date"><?php printNewsDate();?></p>
    	<?php echo getNewsContent(true); ?>
    </td>
</tr>
<?php
    endwhile; 
?>
<tr class="album">
 	<td class="albumdesc"><p><a title="See more news items" href="/news">See more news items...</a></p></td>
</tr>
<?php

} // end new if

?>
</table>
<h2 class="index">Sliced and diced</h2>
<?php

$_randomImageAttempts = 0;
$randomImages = getRandomImagesSet(4);
$randomFilepath1 = getThumbnailURLFromRandomImagesSet($randomImages[0]);
$randomFilepath2 = getThumbnailURLFromRandomImagesSet($randomImages[1]);
$randomFilepath3 = getThumbnailURLFromRandomImagesSet($randomImages[2]);
?>
<table class="indexalbums">
<tr class="album">
	<td class="albumthumb">
		<a href="<?=POPULAR_URL_PATH?>" title="Popular photos"><img src="<?=$randomFilepath1 ?>" alt="Popular photos" /></a>
	 </td><td class="albumdesc">
		<h4><a href="<?=POPULAR_URL_PATH?>" title="Popular photos">Popular photos</a></h4>
		<p>The most popular photos - by week, month, all time, or your ratings!</p>
	</td>
</tr>
<tr class="album">
	<td class="albumthumb">
		<a href="<?=DO_RATINGS_URL_PATH?>" title="Rate my photos"><img src="<?=$randomFilepath2 ?>" alt="Rate my photos" /></a>
	 </td><td class="albumdesc">
		<h4><a href="<?=DO_RATINGS_URL_PATH?>" title="Rate my photos">Rate my photos</a></h4>
		<p>Photo death match - I show you two random photos, you choose which one you like better.</p>
	</td>
</tr>
<tr class="album">
	<td class="albumthumb">
		<a href="<?=RANDOM_ALBUM_PATH?>" title="Random photos"><img src="<?=$randomFilepath3 ?>" alt="Random photos" /></a>
	 </td><td class="albumdesc">
		<h4><a href="<?=RANDOM_ALBUM_PATH?>" title="Random photos">Random photos</a></h4>
		<p>A selection of random photos each time you refresh the page</p>
	</td>
</tr>
</table>
<?php

// dynamic albums
echo "<h2 class=\"index\">Albums</h2>\n";
echo "<table class=\"indexalbums\">\n";

global $totalGalleryAlbumCount;
$randomFilepath4 = getThumbnailURLFromRandomImagesSet($randomImages[3]);
?>
<tr class="album">
	<td class="albumthumb">
		<a href="<?=EVERY_ALBUM_PATH?>" title="All albums"><img src="<?=$randomFilepath4 ?>" alt="All albums" /></a>
	 </td><td class="albumdesc">
		<h4><a href="<?=EVERY_ALBUM_PATH?>" title="All albums">All albums</a></h4>
		<p>Every album - all <?=$totalGalleryAlbumCount?> of them</p>
	</td>
</tr>
<?php

$sql = " SELECT `title`, `desc`, `folder`, thumb FROM " . prefix('albums') . "
		WHERE folder LIKE '%.alb' ORDER BY title";
$dynamicAlbumResults = query_full_array( $sql );

foreach ($dynamicAlbumResults as $album)
{
	$albumTitle = get_language_string($album['title']);
	$albumDesc = get_language_string($album['desc']);
?>
<tr class="album">
	<td class="albumthumb">
		<a href="/<?=$album['folder'];?>/" title="<?php echo gettext('View album:'); ?> <?php echo strip_tags($albumTitle);?>">
		<img src="/cache<?=replace_filename_with_cache_thumbnail_version($album['thumb']); ?>" alt="<?php echo $albumTitle; ?>" title="<?php echo $albumTitle; ?>" /></a>
	</td><td class="albumdesc">
		<h4><a href="/<?=$album['folder'];?>/" title="<?php echo gettext('View album:'); ?> <?php echo $albumTitle; ?>"><?php echo $albumTitle; ?></a></h4>
		<p><?php echo $albumDesc; ?></p>
<? 	if (zp_loggedin())
	{
		echo "<p>";
		echo printLinkHTML('/zp-core/admin-edit.php?page=edit&album=' . urlencode($album['folder']), gettext("Edit details"), NULL, NULL, NULL);
		echo '</p>';
	}		
?>
	</td>

</tr>
<?
}

echo "</table>\n";
include_once('footer.php');


/**
 * Returns a randomly selected image from the gallery. (May be NULL if none exists)
 * @param bool $daily set to true and the picture changes only once a day.
 *
 * @return object
 */
function getRandomImagesSet($toReturn = 5) {
	global $_zp_gallery;
	global $_randomImageAttempts;
	
	$SQLwhere = prefix('images') . ".show=1 
	    AND (" . prefix('images') . ".hitCounter > " . getOption('random_threshold_hitcounter') . " 
	    AND " . prefix('images') . ".ratings_score > " . getOption('random_threshold_ratings') . ")";
	
	$offsetResult = query_full_array( " SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM " . prefix('images') . " WHERE " . $SQLwhere);
	$offset = $offsetResult[0]['offset'];
	
	$sql = " SELECT " . prefix('images') . ".title, " . prefix('images') . ".filename, " . prefix('albums') . ".folder
		FROM " . prefix('images') . "
		INNER JOIN " . prefix('albums') . " ON " . prefix('images') . ".albumid = " . prefix('albums') . ".id 
		WHERE " . $SQLwhere . " 
		LIMIT $offset, $toReturn ";
		
	$randomImagesResult = query_full_array( $sql );
	$imageCount = count($randomImagesResult);
	
	if ($imageCount != $toReturn AND $_randomImageAttempts < 5)
	{
		$_randomImageAttempts++;
		return getRandomImagesSet($toReturn);
	}
	
	return $randomImagesResult;
}

function getThumbnailURLFromRandomImagesSet($array)
{
    if (strlen($array['folder']) > 0)
    {
	    return "/cache/" . $array['folder'] . "/" . replace_filename_with_cache_thumbnail_version($array['filename']);
    }
    
    return "";
}

function getMostRecentImageData()
{
	$thresholdText = $thresholdTextMiddle = '';
	
	// options
	$alertThreshold = getOption('wongm_frontpage_alert_threshold');
	$noticeThreshold = getOption('wongm_frontpage_notice_threshold');
	$limitOnRecentImageThumbnails = getOption('photostream_images_per_page') * 2;
	
	// get most recent image date, as well as the top 24 or so images
	$mostRecentSQL = "SELECT " . prefix('images') . ".mtime AS date, " . 
	                prefix('images') . ".title, " . 
	                prefix('images') . ".filename, " . 
	                prefix('images') . ".hitcounter, " . 
	                prefix('albums') . ".folder 
	                FROM " . prefix('images') . "
		            INNER JOIN " . prefix('albums') . " ON " . prefix('images') . ".albumid = " . prefix('albums') . ".id 
					ORDER BY " . prefix('images') . ".date DESC LIMIT 0, $limitOnRecentImageThumbnails";
	$mostRecentImageData = query_full_array($mostRecentSQL);
	$mostRecentImageTimestamp = $mostRecentImageData[0]['date'];
	
	// determine a random image from the most recent uploaded items
	$i = 0;
	do
	{
    	$randomIndex = rand(0, ($limitOnRecentImageThumbnails - 1));
	    $randomImage = $mostRecentImageData[$randomIndex];
	    
	    // ensure we exclude boring phooos
	    if(!isBoringImage($randomImage['folder']))
	    {
    	    break;
	    }
    	$i++;
	}
	while ($i < $limitOnRecentImageThumbnails);
	
	$thumbnailUrl = getThumbnailURLFromRandomImagesSet($randomImage);
	
	// get date difference
	$mostRecentImageDate = strtotime(Date("Y-m-d", $mostRecentImageTimestamp));
	$todayDate = strtotime(Date("Y-m-d", time()));
    $dateDiff = $todayDate - $mostRecentImageDate;
	$daysSinceUpdate = floor($dateDiff/(60*60*24));
	$formattedUpdatedDate = strftime('%A %B %e, %Y', $mostRecentImageTimestamp);
	
	$plural = "s";
	
	// format text based on date difference
	if ($daysSinceUpdate < $alertThreshold)
	{
		$class = "recent";
		
		if ($daysSinceUpdate == 1)
		{
			$plural = "";
		}
	}
	
	// is it today, or a number of days?
	if ($daysSinceUpdate == 0)
	{
		$daysSinceUpdateText = "(<b>today</b>)";
	}
	else
	{
		$daysSinceUpdateText = "($daysSinceUpdate day$plural ago)";
	}
	
	$lastUpdatedText = "<p class=\"$class\">Last updated $formattedUpdatedDate $daysSinceUpdateText</p>\n";
	
	// get number of recent images
	$recentSQL = "SELECT count(date) AS date FROM " . prefix('images') . " WHERE date > DATE_ADD(CURDATE() , INTERVAL -$alertThreshold DAY)
					UNION ALL
					SELECT count(date) AS date FROM " . prefix('images') . " WHERE date > DATE_ADD(CURDATE() , INTERVAL -$noticeThreshold DAY)";
	$lastImage = query_full_array($recentSQL);
	$periodAlertCount = number_format($lastImage[0]['date'], 0, '.', ',');
	$periodNoticeCount = number_format($lastImage[1]['date'], 0, '.', ',');
	
	if ($periodAlertCount > 0)
	{
		$thresholdText .= "$periodAlertCount photos added in the past $alertThreshold days";
	}
	
	if ($periodNoticeCount > 0)
	{
		$thresholdTextMiddle = "$periodNoticeCount photos added in the past $noticeThreshold days";
	}
	
	if ($thresholdTextMiddle != '')
	{
		if ($thresholdText != '')
		{
			$thresholdText .= ", ";
		}
	
		$thresholdText .= $thresholdTextMiddle;
	}
	
	global $totalGalleryImageCount;
	if ($thresholdText == '')
	{
		$thresholdText .= "$totalGalleryImageCount photos sorted by when they were uploaded.";
	}
	else
	{
		$thresholdText .= ", a total of $totalGalleryImageCount photos sorted by when they were uploaded.";
	}
	
	if ($thresholdText != '')
	{
		$thresholdText = "<p>$thresholdText</p>\n";
	}
	
	return array(
        "content" => $lastUpdatedText . $thresholdText,
        "thumbnailUrl" => $thumbnailUrl
    );
}

function isBoringImage($randomFolderName)
{
    $boring = false;
	$toExclude = explode(',' , getOption('wongm_ratings_folder_exclude'));
	foreach ($toExclude as $folderNameToCheck)
	{
		if (strpos($folderNameToCheck, $randomFolderName) !== false)
		{
			$boring = true;
		}
	}
    return $boring;
}
?>