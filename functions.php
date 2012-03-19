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
DEFINE ('EVERY_ALBUM_PATH', "/gallery/everything");
DEFINE ('CONTACT_URL_PATH', "/contact.php");
DEFINE ('RANDOM_ALBUM_PATH', "/gallery/random");

DEFINE ('UPDATES_URL_PATH', "/gallery/recent");
DEFINE ('WAGON_UPDATES_URL_PATH', "/gallery/recent/wagons");

DEFINE ('RECENT_ALBUM_PATH', "/gallery/recent/albums");

DEFINE ('ALL_TIME_URL_PATH', "/gallery/popular/all-time");
DEFINE ('THIS_MONTH_URL_PATH', "/gallery/popular/this-month");
DEFINE ('THIS_WEEK_URL_PATH', "/gallery/popular/this-week");
DEFINE ('RATINGS_URL_PATH', '/gallery/popular/highest-rated');
DEFINE ('POPULAR_URL_PATH', "/gallery/popular");
DEFINE ('DO_RATINGS_URL_PATH', '/gallery/rate-my-photos');
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

/**
 * Returns the date of the search
 *
 * @param string $format formatting of the date, default 'F Y'
 * @return string
 * @since 1.1
 */
function getFullSearchDate($format='F Y') {
	if (in_context(ZP_SEARCH)) {
		global $_zp_current_search;
		$date = $_zp_current_search->getSearchDate();
		$date = str_replace("/", "", $date);
		if (empty($date)) { return ""; }
		if ($date == '0000-00') { return gettext("no date"); };

		if (sizeof(split('-', $date)) == 3) {
			$format='F d, Y';
		}

		$dt = strtotime($date."-01");
		return date($format, $dt);
	}
	return false;
}


/**
 * WHILE next_album(): context switches to Album.
 * If we're already in the album context, this is a sub-albums loop, which,
 * quite simply, changes the source of the album list.
 * Switch back to the previous context when there are no more albums.

 * Returns true if there are albums, false if none
 *
 * @param bool $all true to go through all the albums
 * @param string $sorttype what you want to sort the albums by
 * @return bool
 * @since 0.6
 */
function next_non_dynamic_album($all=false, $sorttype=null, $direction=null) {
	global $_zp_albums, $_zp_gallery, $_zp_current_album, $_zp_page, $_zp_current_album_restore, $_zp_current_search;
	if (is_null($_zp_albums)) {
		$_zp_albums = $_zp_gallery->getAlbums($all ? 0 : $_zp_page, $sorttype, $direction);

		if (empty($_zp_albums)) { return false; }
		$_zp_current_album_restore = $_zp_current_album;
		$_zp_current_album = new Album($_zp_gallery, array_shift($_zp_albums));
		save_context();
		add_context(ZP_ALBUM);
		return true;
	} else if (empty($_zp_albums)) {
		$_zp_albums = NULL;
		$_zp_current_album = $_zp_current_album_restore;
		restore_context();
		return false;
	} else {
		$_zp_current_album = new Album($_zp_gallery, array_shift($_zp_albums));
		return true;
	}
}
?>