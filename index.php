<?php

$pageTitle = ' - Welcome';
$rssType = 'Gallery';
$rssTitle = 'Recent uploads';

include_once('header.php');
require_once("functions-random.php");

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

/*
echo $sql = " SELECT `title`, `desc`, hitcounter, hitcounter_month, hitcounter_week, thumb  FROM " . prefix('albums') . "
		WHERE folder LIKE '%.album' ORDER BY title";
		
	$dynamicAlbumResults = query_full_array( $sql );
	
echo "<pre>";
	foreach ($dynamicAlbumResults as $album)
	{
		print_r($album);
	}
	echo "</pre>";
*/
drawIndexAlbums('frontpage');
include_once('footer.php');
?>