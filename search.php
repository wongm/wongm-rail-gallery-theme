<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Search';
include_once('header.php'); ?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<a href="<?=SEARCH_URL_PATH?>" title="Gallery Search">Search</a>
	</td><td id="righthead"><?printSearchBreadcrumb();?></td></tr>
</table>
<div id="searchpage">
<?php

$totalAlbums = getNumAlbums();
$totalImages = getNumImages();
$total = $totalAlbums + $totalImages;

if ($totalAlbums > 0)
{
	$albumsText = " - $totalAlbums albums and $totalImages images.";
}
if ($total > 0) 
{
	if (isset($_REQUEST['date']))
	{
		$searchwords = getFullSearchDate();
	} 
	else 
	{ 
		$searchwords = getSearchWords(); 
	}
 	echo '<p>'.sprintf(gettext('%2$u total matches for <em>%1$s</em>'), $searchwords, $total)." $albumsText</p>";
}
drawWongmAlbumNextables(false);

if ($totalAlbums > 0)
{
	echo "<table class=\"indexalbums\">\n";
	while (next_album())
	{
		if (is_null($firstAlbum)) 
		{
			$lastAlbum = albumNumber();
			$firstAlbum = $lastAlbum;
		} 
		else 
		{
			$lastAlbum++;
		}
		drawWongmAlbumRow();
	}
	echo "</table>";
}
?>
<div id="images">
<?php drawWongmGridImages(); ?>
</div>
<?php
if (function_exists('printSlideShowLink')) {
	echo "<p align=\"center\">";
	printSlideShowLink(gettext('View Slideshow'));
	echo "</p>";
}
if ($totalImages == 0 AND $totalAlbums == 0) 
{
	echo "<p>".gettext("Sorry, no image matches. Try refining your search.")."</p>";
}
drawWongmAlbumNextables(true);
?>
</div>
<?php include_once('footer.php'); ?>