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
<h3>News</h3>
<table class="indexalbums">
<tr class="album">
	<td class="albumthumb">
		<a href="<?=UPDATES_URL_PATH?>" title="Recent uploads"><img src="<?=$filepath?>" alt="Recent uploads" /></a>
	 </td><td class="albumdesc">
		<h4><a href="<?=UPDATES_URL_PATH?>" title="Recent uploads">Recent uploads</a></h4>
		<p><small><?=getMostRecentImageDate() ?></small></p>
		<p>All <?=$photosNumber?> photos sorted by when they were uploaded</p>
	</td>
</tr>
<?					
while (next_news() AND $i++ < 2): ;?>
<tr class="album">
 	<? if ($i == 1) { ?>
	<td class="albumthumb" rowspan="2" valign="top"></td>
	<? } ?>
 	<td class="albumdesc">
    	<h4><?php printNewsTitleLink(); ?></h4>
    	<p><small><?php printNewsDate();?></small></p>
    	<p><?php printNewsContent(); ?></p>
    	<p><?php printNewsReadMoreLink(); ?></p>
    	<?php printCodeblock(1); ?>
    </td>
</tr>    
<?php
	
endwhile; 
echo "</table>\n";

echo "<h3>Sliced and diced</h3>\n";

$randomFilepath3 = getThumbnailURLFromRandomImagesSet($_randomImages[1]);
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
		<a href="<?=RANDOM_ALBUM_PATH?>" title="Random photos"><img src="<?=$randomFilepath3?>" alt="Random photos" /></a>
	 </td><td class="albumdesc">
		<h4><a href="<?=RANDOM_ALBUM_PATH?>" title="Random photos">Random photos</a></h4>
		<p>A selection of random photos each time you refresh the page</p>
	</td>
</tr>
</table>
<?php

// dynamic albums
echo "<h3>Albums</h3>\n";
echo "<table class=\"indexalbums\">\n";

global $albumNumber;

$randomFilepath6 = getThumbnailURLFromRandomImagesSet($_randomImages[4]);
?>
<tr class="album">
	<td class="albumthumb">
		<a href="<?=EVERY_ALBUM_PATH?>" title="All albums"><img src="<?=$randomFilepath6?>" alt="All albums" /></a>
	 </td><td class="albumdesc">
		<h4><a href="<?=EVERY_ALBUM_PATH?>" title="All albums">All albums</a></h4>
	 	<p><small><?=getMostRecentImageDate();?></small></p>
		<p>Every album - <?=$albumNumber?> of them</p>
	</td>
</tr>
<?php

$sql = " SELECT `title`, `desc`, `folder`, thumb FROM " . prefix('albums') . "
		WHERE folder LIKE '%.album' ORDER BY title";
$dynamicAlbumResults = query_full_array( $sql );

foreach ($dynamicAlbumResults as $album)
{
	$imgURL = str_replace('.jpg', '_250_thumb.jpg', '/cache'.$album['thumb']);
	
?>
<tr class="album">
	<td class="albumthumb">
		<a href="/<?=$album['folder'];?>/" title="<?php echo gettext('View album:'); ?> <?php echo strip_tags($album['title']);?>">
		<img src="<?=$imgURL; ?>" alt="<?php echo $album['title']; ?>" title="<?php echo $album['title']; ?>" /></a>
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
	
	$SQLwhere = prefix('images') . ".show=1 AND (" . prefix('images') . ".hitCounter > " . HITCOUNTER_THRESHOLD . " AND " . prefix('images') . ".ratings_score > " . RATINGS_THRESHOLD . ")";
	
	$offset_result = mysql_query( " SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM " . prefix('images') . " WHERE " . $SQLwhere);
	$offset_row = mysql_fetch_object( $offset_result );
	$offset = $offset_row->offset;
	
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
	return '/'.$array['folder']."/image/thumb/".$array['filename'];
}
?>