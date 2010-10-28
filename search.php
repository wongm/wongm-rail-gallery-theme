<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Search';
include_once('header.php'); ?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<a href="<?=SEARCH_URL_PATH?>" title="Gallery Search">Search</a>
	</td><td id="righthead"><? printSearchForm();?></td></tr>
</table>
<?php

$totalAlbums = getNumAlbums();
$totalImages = getNumImages();
$totalItems = $totalAlbums + $totalImages;

if ($totalAlbums > 0)
{
	$albumsText = " - $totalAlbums albums and $totalImages images.";
}
if ($totalItems > 0) 
{
	if (isset($_REQUEST['date']))
	{
		$searchwords = getFullSearchDate();
	} 
	else 
	{ 
		$searchwords = getSearchWords(); 
	}
}

if (strlen($searchwords) == 0)
{
	$leadingIntroText = "<h2>Search</h2>";
}
else
{
	$leadingIntroText = "<h2>Search results</h2>\n";
	$leadingIntroText .= '<p>'.sprintf(gettext('%2$u total matches for <em>%1$s</em>'), $searchwords, $totalItems)." $albumsText</p>";
}
?>
<div class="topbar">
	<?php echo $leadingIntroText; ?>
</div>
<div id="searchpage">
<?
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
<?php drawWongmGridImages($totalImages); ?>
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

if (hasNextPage() OR hasPrevPage())
{
?>
<table class="nextables"><tr id="pagelinked"><td>
	<?php if (hasPrevPage()) { ?> <a class="prev" href="<?=getPrevPageURL();?>" title="Previous Page"><span>&laquo;</span> Previous</a> <?php } ?>
	</td><td><?php printPageList(); ?></td><td>
	<?php if (hasNextPage()) { ?> <a class="next" href="<?=getNextPageURL();?>" title="Next Page">Next <span>&raquo;</span></a><?php } ?>
</td></tr></table>
<?
}
?>
</div>
<?php include_once('footer.php'); ?>