<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

global $_zp_current_search;
if (isset($_zp_current_search))
{
    // ensure the 'archive' page displays images in morning to nightime order
    if (isset($_REQUEST['date']))
    {
        $_zp_current_search->setSortDirection(false);
    }
}

$albumsText = $searchwords = '';
$totalAlbums = getNumAlbums();
$totalImages = getNumImages();
$totalItems = $totalAlbums + $totalImages;

$breadcrumbLinks = '<a href="' . SEARCH_URL_PATH . '" title="Gallery Search">Search</a>';
$leadingIntroText = '<p>'.sprintf(gettext('%2$u total matches for <em>%1$s</em>'), $searchwords, $totalItems)."$albumsText, ordered by date.</p>";

// don't index search pages
$noIndex = true;

if ($totalAlbums > 0)
{
	$albumsText = " - $totalAlbums albums and $totalImages images";
}
if ($totalItems > 0)
{
	if (isset($_REQUEST['date']))
	{
		$joiner = "in";
		if (sizeof(explode('-', $_REQUEST['date'])) == 3) {
			$joiner = "on";
		}
		
		$searchwords = getFullSearchDate();
		$pageTitle = $searchwords;
		$leadingIntroText = "<h2>Gallery archive</h2>\n";
		$breadcrumbLinks = '<a href="' . ARCHIVE_URL_PATH . '" title="Gallery Archive">Archive</a> &raquo; ' . $searchwords;
		$leadingIntroText .= '<p>'.sprintf(gettext('%1$u photos taken %2$s %3$s'), $totalItems, $joiner, $searchwords)."$albumsText.</p>";
		
		// but do index pages for a specific date
		$noIndex = false;
	}
	else
	{
		$searchwords = getSearchWords();
		$pageTitle = 'Search results - ' . $searchwords;
		$leadingIntroText = "<h2>Search results</h2>\n";
		
		if ((strlen($searchwords) > 3 || preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $searchwords)) && getCurrentPage() == 1)
		{
			// but do index search results for 'real' words, but only first page
			$noIndex = false;
		}
	}
}

if (strlen($searchwords) == 0)
{
	$pageTitle = 'Search';
	$leadingIntroText = "<h2>Search</h2>";
}

include_once('header.php');

if (isset($_REQUEST['s'])) { ?>
<script type="text/javascript">$(document).ready(function() {
	document.getElementById('search_input').value = '<? echo str_replace("\"", "", getSearchWords()); ?>';
});</script>
<?php
}
 ?>
<div class="headbar">
	<span id="breadcrumb">
		<a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo; <?php echo $breadcrumbLinks; ?>
	</span>
	<span id="righthead"><?php echo printSearchForm(); ?></span>
</div>
<div class="topbar">
	<?php echo $leadingIntroText; ?>
</div>
<div id="searchpage">
<?php
if ($totalAlbums > 0)
{
	echo "<div id=\"indexalbums\">\n";
	$firstAlbum = "";
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
	echo "</div>";
}

drawWongmGridImages('search', $totalImages); 

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