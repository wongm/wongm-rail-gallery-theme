<?php 
/*
 * Present the different types of ordered photo pages
 * - highest this month
 * - highest this week
 * - highest of all time
 * - highest ranking
 * - show most recent uploaded photos
 *
 */ 
$startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

require_once("functions-wongmrailgallery.php");

// set up variables
$recentPageNumber = $_REQUEST['page'];
$pageType = $_REQUEST['type'];
$pageTypeModifier = $_REQUEST['period'];
$start = $_REQUEST['start'];
$count = $_REQUEST['count'];

// various modifiers for the recent upload pages
if (isset($_REQUEST['double']))
{
	$pageTypeModifier = 'double';
}

if (isset($_REQUEST['caption']))
{
	$pageTypeModifier = $_REQUEST['caption'];
}

// used when drawing out the gallery item later on
$galleryType = $pageTypeModifier;

// draw gallery of hitcounter or rating based ranked pages
if ($pageType == 'popular')
{
	$pageTitle = $popularImageText[$pageTypeModifier]['title'];
	$leadingIntroText = $popularImageText[$pageTypeModifier]['text'];
	$nextURL = $popularImageText[$pageTypeModifier]['url'];
	$pageBreadCrumb = "<a href=\"".POPULAR_URL_PATH."\" title=\"Popular photos\">Popular photos</a>
		&raquo; <a href=\"$nextURL\" title=\"$leadingIntroText\">$leadingIntroText</a>";
	
	if ($pageTypeModifier == 'ratings')
	{
		$trailingIntroText = '. '.RATINGS_TEXT;
	}
}
// get date based ranked pages
else if ($pageType == '')
{
	if ($pageTypeModifier == 'wagons')
	{
		$nextURL = WAGON_UPDATES_URL_PATH;
		$pageTitle = 'Recent uploads - Wagons and containers';
		$subheading = "Recent wagons and containers";
		$leadingIntroText = "Wagons and containers, sorted by upload date to the site (not when they were taken)";
		$pageBreadCrumb = "<a href=\"".UPDATES_URL_PATH."\" title=\"Recent uploads\">Recent uploads</a>
			&raquo; <a href=\"$nextURL\" title=\"Wagons and containers\">Wagons and containers</a>";
	}
	else
	{
		$nextURL = UPDATES_URL_PATH;
		$leadingIntroText = $pageTitle = 'Recent uploads';
		$pageBreadCrumb = "<a href=\"$nextURL\" title=\"$leadingIntroText\">$leadingIntroText</a>";

		$trailingIntroText = ". For recent wagon photos go to the <a href=\"".WAGON_UPDATES_URL_PATH."\" title=\"wagons and containers page\">wagons and containers page</a>";

		if ( zp_loggedin() ) {
			$adminOnlyText = '<p><a href="'.$nextURL.'/?caption=images">Uncaptioned images</a><br>
				<a href="'.$nextURL.'/?caption=albums">Albums with uncaptioned images</a><br>
				<a href="'.$nextURL.'/?double=">Duplicate images</a></p>';
		}
	}

	$rssType = 'Gallery';
	$rssTitle = 'Recent uploads';
}

if ($subheading == "")
{
	$subheading = $leadingIntroText;
}

$trailingIntroText .= '.';

$pageTitle = " - $pageTitle";
include_once('header.php'); 
require_once("functions-search.php");
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<?=$pageBreadCrumb?>
	</td><td id="righthead"><?printSearchForm();?></td></tr>
</table>
<div class="topbar">
	<h2><?=$subheading?></h2>
</div>
<?
if (!is_numeric($recentPageNumber) OR $recentPageNumber < 1)
{
	$recentPageNumber = 1;
}

if ($recentPageNumber == '' OR $recentPageNumber <= 1 OR !is_numeric($recentPageNumber))
{
	$currentImageResultIndex = 0;
}
else
{
	$currentImageResultIndex = ($recentPageNumber*MAXIMAGES_PERPAGE)-MAXIMAGES_PERPAGE;
}

// get gallery results, number of records, total number of records, and modified value of the next URL
$galleryResults = getGalleryUploadsResults($pageType, $pageTypeModifier, $nextURL, $start, $count, $currentImageResultIndex);

if ($galleryResults['galleryResultCount'] == 0 AND $recentPageNumber != 'fail')
{
	$currentImageResultIndex = 0;//getRecent('fail');
}
else
{
	echo "<p>$leadingIntroText, photos ".getNumberCurrentDispayedRecords(MAXIMAGES_PERPAGE, $galleryResults['galleryResultCount'], $recentPageNumber-1)."$trailingIntroText</p>";
	echo $adminOnlyText;
	
	drawImageGallery($galleryResults['galleryResult'], $galleryType);
	galleryPageNavigationLinks($currentImageResultIndex, $galleryResults['maxImagesCount'], $galleryResults['galleryResultCount'], $galleryResults['nextURL']);
}
include_once('footer.php'); 
?>