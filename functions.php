<?php

//******************************************************************************
// Miscellaneous functions for the ZenPhoto gallery that I need
//
// For wongm.railgeelong.com
//
// V 1.0.0
//
//******************************************************************************

require_once('functions-gallery-formatting.php');

DEFINE ('ARCHIVE_URL_PATH', "/page/archive");
DEFINE ('SEARCH_URL_PATH', "/page/search");
DEFINE ('EVERY_ALBUM_PATH', "/page/everything");
DEFINE ('CONTACT_URL_PATH', "/contact.php");
DEFINE ('RANDOM_ALBUM_PATH', "/page/random");

DEFINE ('UPDATES_URL_PATH', "/page/recent-uploads");
DEFINE ('WAGON_UPDATES_URL_PATH', "/page/recent-wagons");

DEFINE ('RECENT_ALBUM_PATH', "/page/recent-albums");

DEFINE ('ALL_TIME_URL_PATH', "/page/popular-all-time");
DEFINE ('THIS_MONTH_URL_PATH', "/page/popular-this-month");
DEFINE ('THIS_WEEK_URL_PATH', "/page/popular-this-week");
DEFINE ('RATINGS_URL_PATH', '/page/popular-by-ratings');
DEFINE ('POPULAR_URL_PATH', "/page/popular");
DEFINE ('DO_RATINGS_URL_PATH', '/page/rate-my-photos');
DEFINE ('GALLERY_PATH', '');

DEFINE ('RATINGS_TEXT', 'You can rate photos <a href="' . DO_RATINGS_URL_PATH . '">here</a>');

$popularImageText['all-time']['url'] = ALL_TIME_URL_PATH;
$popularImageText['all-time']['title'] = 'Popular photos - Most viewed of all time';
$popularImageText['all-time']['text'] = 'Most viewed of all time';
$popularImageText['all-time']['type'] = 'popular';
$popularImageText['all-time']['order'] = "i.hitcounter DESC";
$popularImageText['all-time']['where'] = "i.hitcounter > " . getOption('popular_threshold_hitcounter');

$popularImageText['this-month']['url'] = THIS_MONTH_URL_PATH;
$popularImageText['this-month']['title'] = 'Popular photos - Most viewed this month';
$popularImageText['this-month']['text'] = 'Most viewed this month';
$popularImageText['this-month']['type'] = 'popular';
$popularImageText['this-month']['order'] = "i.hitcounter_month DESC";
$popularImageText['this-month']['where'] = "i.hitcounter_month > " . getOption('popular_threshold_hitcounter');

$popularImageText['this-week']['url'] = THIS_WEEK_URL_PATH;
$popularImageText['this-week']['title'] = 'Popular photos - Most viewed this week';
$popularImageText['this-week']['text'] = 'Most viewed this week';
$popularImageText['this-week']['type'] = 'popular';
$popularImageText['this-week']['order'] = "i.hitcounter_week DESC";
$popularImageText['this-week']['where'] = "i.hitcounter_week > " . getOption('popular_threshold_hitcounter');

$popularImageText['ratings']['url'] = RATINGS_URL_PATH;
$popularImageText['ratings']['title'] = 'Popular photos - Highest rated';
$popularImageText['ratings']['text'] = 'Highest rated';
$popularImageText['ratings']['type'] = 'popular';
$popularImageText['ratings']['order'] = "i.ratings_score DESC, i.hitcounter DESC";
$popularImageText['ratings']['where'] = "i.ratings_view > " . getOption('popular_threshold_hitcounter');

$popularImageText['uploads']['url'] = UPDATES_URL_PATH;
$popularImageText['uploads']['title'] = 'Recent uploads';
$popularImageText['uploads']['text'] = 'Recent uploads';
$popularImageText['uploads']['subtext'] = 'For recent wagon photos go to the <a href="' . WAGON_UPDATES_URL_PATH . '" title="wagons and containers page">wagons and containers page</a>.';

$popularImageText['wagons']['url'] = WAGON_UPDATES_URL_PATH;
$popularImageText['wagons']['title'] = 'Recent uploads - Wagons';
$popularImageText['wagons']['text'] = 'Wagons and containers';
$popularImageText['wagons']['type'] = 'recent';
$popularImageText['wagons']['order'] = "i.mtime DESC";
$popularImageText['wagons']['where'] = "folder LIKE 'wagons%'";
$popularImageText['wagons']['subtext'] = 'Sorted by upload date to the site (not when they were taken).';

zp_register_filter('checkPageValidity', 'wongmTheme::checkLinks');
zp_register_filter('admin_toolbox_album', 'wongmTheme::addAlbumLink');
zp_register_filter('admin_toolbox_global', 'wongmTheme::addGlobalLink');

/**
 * Plugin option handling class
 *
 */
class wongmTheme {
	
	static function checkLinks($count, $gallery_page, $page) {
    	switch (stripSuffix($gallery_page))
    	{
        	case 'archive':
        	case 'everything':
        	case 'popular-all-time':
        	case 'popular-by-ratings':
        	case 'popular-ratings':
        	case 'popular-this-month':
        	case 'popular-this-week':
        	case 'recent-albums':
        	case 'recent-duplicates':
        	case 'recent-resize':
        	case 'recent-shrink':
        	case 'recent-shrink-albums':
        	case 'recent-uncaptioned':
        	case 'recent-uncaptioned-albums':
        	case 'recent-uploads':
        	case 'recent-wagons':
        	case 'rss-updates':
        	    return true;
		}
	}

    static function addAlbumLink($albumname) {
    }
    
    static function addGlobalLink() {
    	echo "<li>";
    	printLinkHTML(WEBPATH.'/page/recent-resize', 'Images to resize', NULL, NULL, NULL);
    	echo "</li><li>";
    	printLinkHTML(WEBPATH.'/page/recent-shrink', 'Images to shrink', NULL, NULL, NULL);
    	echo "</li><li>";
    	printLinkHTML(WEBPATH.'/page/recent-shrink-albums', 'Images to shrink by album', NULL, NULL, NULL);
    	echo "</li><li>";
    	printLinkHTML(WEBPATH.'/page/recent-uncaptioned', 'Uncaptioned images', NULL, NULL, NULL);
    	echo "</li><li>";
    	printLinkHTML(WEBPATH.'/page/recent-uncaptioned-albums', 'Uncaptioned albums', NULL, NULL, NULL);
    	echo "</li><li>";
    	printLinkHTML(WEBPATH.'/page/recent-duplicates', 'Duplicate images', NULL, NULL, NULL);
    	echo "</li>";
    }
}

function printFacebookTag()
{
	$protocol = SERVER_PROTOCOL;
	if ($protocol == 'https_admin') {
		$protocol = 'https';
	}
	$path = $protocol . '://' . $_SERVER['HTTP_HOST'] . WEBPATH . getDefaultSizedImage();
	$description = "Photographs of trains and railway infrastructure from around Victoria, Australia";
	if (strlen(getImageDesc()) > 0) {
		$description = strip_tags(getImageDesc());
	}
	echo "<meta name=\"og:image\" content=\"$path\" />\n";
	echo "<meta name=\"og:title\" content=\"" . getImageTitle() . "\" />\n";	
	echo "<meta name=\"og:description\" content=\"$description\" />\n";
	echo "<meta name=\"twitter:card\" content=\"photo\">\n";
	echo "<meta name=\"twitter:site\" content=\"aussiewongm\">\n";
	echo "<meta name=\"twitter:creator\" content=\"aussiewongm\">\n";
	echo "<meta name=\"twitter:title\" content=\"" . getImageTitle() . "\">\n";
	echo "<meta name=\"twitter:image:src\" content=\"$path\">\n";
	echo "<meta name=\"twitter:domain\" content=\"" . getGalleryTitle() . "\">\n";
}

function drawWongmImageCell($pageType)
{
	global $_zp_current_image;
	
	$albumLinkText = getImageAlbumLink();
	
	// if recent uploads and not logged in
	if ($pageType == 'uploads' && !zp_loggedin())
	{
		$hitcounterText = '';
	}
	// other types of recent items / most viewed pages
	else if ($pageType != 'ratings')
	{
		$hitcounterText = getRollingHitCounter($_zp_current_image, $pageType);
	}
	// ratings instead
	else
	{
		$hitcounterText = getDeathmatchRatingsText();
	}
	
	if (strlen($hitcounterText) > 0)
	{
		$hitcounterText = '<br/>' . $hitcounterText;
	}
?>
<td class="image">
	<div class="imagethumb"><a href="<?=getImageURL();?>" title="<?=getImageTitle();?>">
		<img src="<? echo getImageThumb() ?>" title="<?=getImageTitle();?>" alt="<?=getImageTitle();?>" />
	</a></div>
	<div class="imagetitle">
		<h4><a href="<?=getImageURL();?>" title="<?=getImageTitle();?>"><?php printImageTitle(); ?></a></h4>
		<?php echo printImageDescWrapped(); ?>
		<small><?php printImageDate(); ?><?php echo $hitcounterText ?></small>
		<?php echo $albumLinkText ?>
	</div>
</td>
<?php
}

function drawWongmListSubalbums()
{
	$toReturn = 0;
	
	if (getNumAlbums() > 0)
	{
?>
<!-- Sub-Albums -->
<table class="indexalbums">
<?php
	while (next_album()):
		drawWongmAlbumRow();
		$toReturn++;
	endwhile;
?>
</table>
<?
	}
	
	return $toReturn;
	
}	/// end function

?>