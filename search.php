<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

global $_zp_current_search;
if (isset($_zp_current_search))
{
    // ensure the 'archive' page displays images in morning to nightime order
    if (isset($_REQUEST['date']))
    {
        $_zp_current_search->setSortDirection(false);
    }
    // dates show the newest item first
    else if (isset($_REQUEST['words']))
    {
        if (strpos($_REQUEST['words'],'js-agent.newrelic.com') !== false) {
            http_response_code(404);
            die();
        }
        $_zp_current_search->setSortDirection(true);  
    }
    // let everything else use the defaults
    else
    {
        $_zp_current_search->setSortDirection(false);
    }
}

$albumsText = $searchwords = '';
$totalAlbums = getNumAlbums();
$totalImages = getNumImages();
$totalItems = $totalAlbums + $totalImages;

if ($totalAlbums > 0)
{
	$albumsText = " - $totalAlbums albums and $totalImages images";
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
	$pageTitle = 'Search';
	$leadingIntroText = "<h2>Search</h2>";
}
else
{
	$pageTitle = 'Search results - ' . $searchwords;
	$leadingIntroText = "<h2>Search results</h2>\n";
	$leadingIntroText .= '<p>'.sprintf(gettext('%2$u total matches for <em>%1$s</em>'), $searchwords, $totalItems)."$albumsText, ordered by date.</p>";
}

include_once('header.php');

if (isset($_REQUEST['words'])) { ?>
<script type="text/javascript">$(document).ready(function() {
	document.getElementById('search_input').value = '<? echo str_replace("\"", "", getSearchWords()); ?>';
});</script>
<? 
}
 ?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<a href="<?=SEARCH_URL_PATH?>" title="Gallery Search">Search</a>
	</td><td id="righthead"><? printSearchForm();?></td></tr>
</table>
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
	printPageListWithNav("« ".gettext("Prev"), gettext("Next")." »");
}
?>
</div>
<?php include_once('footer.php'); ?>