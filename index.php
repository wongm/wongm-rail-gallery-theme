<?php

$pageTitle = ' - Welcome';
$rssType = 'Gallery';
$rssTitle = 'Recent uploads';

include_once('header.php');

global $_randomImages, $_randomImageAttempts;

$_randomImageAttempts = 0;
$_randomImages = getRandomImagesSet(5);
$filepath = getThumbnailURLFromRandomImagesSet($_randomImages[0]);
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; Home</td>
	<td><?printSearchForm();?></td></tr>
</table>
<h2 class="index">News</h2>
<table class="indexalbums">
<tr class="album">
	<td class="albumthumb">
		<a href="<?=UPDATES_URL_PATH?>" title="Recent uploads"><img src="<?=$filepath?>" alt="Recent uploads" /></a>
	 </td><td class="albumdesc">
		<h4><a href="<?=UPDATES_URL_PATH?>" title="Recent uploads">Recent uploads</a></h4>
		<? getMostRecentImageDate(); ?>
	</td>
</tr>
<?					
while (next_news() AND $i++ < getOption('wongm_news_count')): ;?>
<tr class="album">
 	<? if ($i == 1) { ?>
	<td class="albumthumb" rowspan="<?=getOption('wongm_news_count') + 1?>" valign="top"></td>
	<? } ?>
 	<td class="albumdesc">
    	<h4><?php printNewsTitleLink(); ?></h4>
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
</table>
<?php

echo "<h2 class=\"index\">Sliced and diced</h2>\n";

$randomFilepath2 = getThumbnailURLFromRandomImagesSet($_randomImages[1]);
$randomFilepath4 = getThumbnailURLFromRandomImagesSet($_randomImages[2]);
$randomFilepath5 = getThumbnailURLFromRandomImagesSet($_randomImages[3]);
?>
<table class="indexalbums">
<tr class="album">
	<td class="albumthumb">
		<a href="<?=POPULAR_URL_PATH?>" title="Popular photos"><img src="<?=$randomFilepath4?>" alt="Popular photos" /></a>
	 </td><td class="albumdesc">
		<h4><a href="<?=POPULAR_URL_PATH?>" title="Popular photos">Popular photos</a></h4>
		<p>The most popular photos - by week, month, all time, or your ratings!</p>
	</td>
</tr>
<tr class="album">
	<td class="albumthumb">
		<a href="<?=DO_RATINGS_URL_PATH?>" title="Rate my photos"><img src="<?=$randomFilepath5?>" alt="Rate my photos" /></a>
	 </td><td class="albumdesc">
		<h4><a href="<?=DO_RATINGS_URL_PATH?>" title="Rate my photos">Rate my photos</a></h4>
		<p>Photo death match - I show you two random photos, you choose which one you like better.</p>
	</td>
</tr>
<tr class="album">
	<td class="albumthumb">
		<a href="<?=RANDOM_ALBUM_PATH?>" title="Random photos"><img src="<?=$randomFilepath2?>" alt="Random photos" /></a>
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

global $albumNumber;

$randomFilepath6 = getThumbnailURLFromRandomImagesSet($_randomImages[4]);
?>
<tr class="album">
	<td class="albumthumb">
		<a href="<?=EVERY_ALBUM_PATH?>" title="All albums"><img src="<?=$randomFilepath6?>" alt="All albums" /></a>
	 </td><td class="albumdesc">
		<h4><a href="<?=EVERY_ALBUM_PATH?>" title="All albums">All albums</a></h4>
		<p>Every album - all <?=$albumNumber?> of them</p>
	</td>
</tr>
<?php

$sql = " SELECT `title`, `desc`, `folder`, thumb FROM " . prefix('albums') . "
		WHERE folder LIKE '%.alb' ORDER BY title";
$dynamicAlbumResults = query_full_array( $sql );

foreach ($dynamicAlbumResults as $album)
{
?>
<tr class="album">
	<td class="albumthumb">
		<a href="/<?=$album['folder'];?>/" title="<?php echo gettext('View album:'); ?> <?php echo strip_tags($album['title']);?>">
		<img src="/cache<?=replace_filename_with_cache_thumbnail_version($album['thumb']); ?>" alt="<?php echo $album['title']; ?>" title="<?php echo $album['title']; ?>" /></a>
	</td><td class="albumdesc">
		<h4><a href="/<?=$album['folder'];?>/" title="<?php echo gettext('View album:'); ?> <?php echo $album['title']; ?>"><?php echo $album['title']; ?></a></h4>
		<p><?php echo $album['desc']; ?></p>
<? 	if (zp_loggedin())
	{
		echo "<p>";
		echo printLink($zf . '/zp-core/admin-edit.php?page=edit&album=' . urlencode($album['folder']), gettext("Edit details"), NULL, NULL, NULL);
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
	
	$SQLwhere = prefix('images') . ".show=1 AND (" . prefix('images') . ".hitCounter > " . getOption('random_threshold_hitcounter') . " AND " . prefix('images') . ".ratings_score > " . getOption('random_threshold_ratings') . ")";
	
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
	return "/cache/" . $array['folder'] . "/" . replace_filename_with_cache_thumbnail_version($array['filename']);
}

function getMostRecentImageDate()
{
	global $photosNumber;
	
	// options
	$alertThreshold = getOption('wongm_frontpage_alert_threshold');
	$noticeThreshold = getOption('wongm_frontpage_notice_threshold');
	
	// get most recent image date
	$recentSQL = "SELECT " . prefix('images') . ".mtime AS date FROM " . prefix('images') . "
					ORDER BY " . prefix('images') . ".date DESC LIMIT 0 , 1";
	$lastImage = query_full_array($recentSQL);
	$mostRecentImageDate = $lastImage[0]['date'];
	
	// get date difference	
	$dateDiff = time() - $mostRecentImageDate;
	$daysSinceUpdate = floor($dateDiff/(60*60*24));
	$formattedUpdatedDate = strftime('%A %B %e, %Y', $mostRecentImageDate);
	
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
	
	echo "<p class=\"$class\">Last updated $formattedUpdatedDate $daysSinceUpdateText</p>\n";
	
	// get number of recent images
	$recentSQL = "SELECT count(date) AS date FROM " . prefix('images') . " WHERE date > DATE_ADD(CURDATE() , INTERVAL -$alertThreshold DAY)
					UNION ALL
					SELECT count(date) AS date FROM " . prefix('images') . " WHERE date > DATE_ADD(CURDATE() , INTERVAL -$noticeThreshold DAY)";
	$lastImage = query_full_array($recentSQL);
	$periodAlertCount = $lastImage[0]['date'];
	$periodNoticeCount = $lastImage[1]['date'];
	
	if ($periodAlertCount > 0)
	{
		$toPrint .= "$periodAlertCount photos added in the past $alertThreshold days";
	}
	
	if ($periodNoticeCount > 0)
	{
		$toPrintMiddle = "$periodNoticeCount photos added in the past $noticeThreshold days";
	}
	
	if ($toPrintMiddle != '')
	{
		if ($toPrint != '')
		{
			$toPrint .= ", ";
		}
	
		$toPrint .= $toPrintMiddle;
	}
	
	if ($toPrint == '')
	{
		$toPrint .= "$photosNumber photos sorted by when they were uploaded.";
	}
	else
	{
		$toPrint .= ", a total of $photosNumber photos sorted by when they were uploaded.";
	}
	
	
	
	if ($toPrint != '')
	{
		echo "<p>$toPrint</p>\n";
	}
}
?>