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

// dynamic from the DB
define ('MAXIMAGES_PERPAGE', $_zp_options['images_per_page']);
define ('MAXIMAGES_PERRANDOM', 12);
define ('MAXALBUMS_PERPAGE', $_zp_options['albums_per_page']);
define ('THUMBNAIL_IMAGE_SIZE', $_zp_options['thumb_size']);
define ('TIME_FORMAT', $_zp_options['date_format']);

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

DEFINE ('HITCOUNTER_SHOW_THRESHOLD', 3);

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
	echo "<meta property=\"og:image\" content=\"$path\" />\n";
	echo "<meta property=\"og:title\" content=\"" . getImageTitle() . "\" />\n";	
	echo "<meta property=\"og:description\" content=\"$description\" />\n";
}


?>