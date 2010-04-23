<?php

//******************************************************************************
// Miscellaneous functions for the ZenPhoto gallery that I need
//
// For wongm.railgeelong.com
//
// V 1.0.0
//
//******************************************************************************

// dynamic from the DB
define ('MAXIMAGES_PERPAGE', $_zp_options['images_per_page']);
define ('MAXIMAGES_PERRANDOM', 12);
define ('MAXALBUMS_PERPAGE', $_zp_options['albums_per_page']);
define ('THUMBNAIL_IMAGE_SIZE', $_zp_options['thumb_size']);
define ('TIME_FORMAT', $_zp_options['date_format']);

DEFINE ('ARCHIVE_URL_PATH', "/gallery/archive");
DEFINE ('SEARCH_URL_PATH', "/gallery/search");
DEFINE ('UPDATES_URL_PATH', "/gallery/recent");
DEFINE ('EVERY_ALBUM_PATH', "/gallery/everything");
DEFINE ('CONTACT_URL_PATH', "/contact.php");
DEFINE ('RANDOM_ALBUM_PATH', "/gallery/random");

DEFINE ('RECENT_ALBUM_PATH', "/gallery/recent/albums");

DEFINE ('ALL_TIME_URL_PATH', "/gallery/popular/all-time");
DEFINE ('THIS_MONTH_URL_PATH', "/gallery/popular/this-month");
DEFINE ('THIS_WEEK_URL_PATH', "/gallery/popular/this-week");
DEFINE ('RATINGS_URL_PATH', '/gallery/popular/highest-rated');
DEFINE ('POPULAR_URL_PATH', "/gallery/popular");
DEFINE ('DO_RATINGS_URL_PATH', '/gallery/rate-my-photos');
DEFINE ('GALLERY_PATH', '');

DEFINE ('RATINGS_TEXT', 'You can rate photos <a href="' . DO_RATINGS_URL_PATH . '">here</a>.');

$popularImageText['all-time']['url'] = ALL_TIME_URL_PATH;
$popularImageText['all-time']['title'] = 'Popular photos - Most viewed of all time';
$popularImageText['all-time']['text'] = 'Most viewed of all time';

$popularImageText['this-month']['url'] = THIS_MONTH_URL_PATH;
$popularImageText['this-month']['title'] = 'Popular photos - Most viewed this month';
$popularImageText['this-month']['text'] = 'Most viewed this month';

$popularImageText['this-week']['url'] = THIS_WEEK_URL_PATH;
$popularImageText['this-week']['title'] = 'Popular photos - Most viewed this week';
$popularImageText['this-week']['text'] = 'Most viewed this week';

$popularImageText['ratings']['url'] = RATINGS_URL_PATH;
$popularImageText['ratings']['title'] = 'Popular photos - Highest rated';
$popularImageText['ratings']['text'] = 'Highest rated';

/**
 * Returns the raw title of the current image.
 *
 * @return string
 */
function getImageAlbumLink() {
	if(!in_context(ZP_IMAGE)) return false;
	global $_zp_current_image;
	$title = $_zp_current_image->getAlbum()->getTitle();
	$folder = $_zp_current_image->getAlbum()->getFolder();
	return "<br/>In album: <a href=\"/$folder\">$title</a>";
}



function drawWongmListSubalbums()
{
?>
<!-- Sub-Albums -->
<table class="indexalbums">
<?php
	// neater for when only 4 items
	if (getNumAlbums() == 4)
	{
		$i = 1;
	}
	else
	{
		$i = 0;
	}
	while (next_album()):
	if ($i == 0)
	{
		echo '<tr>';
	}
	drawWongmAlbumRow();
	if ($i == 2)
	{
		echo "</tr>\n";
		$i = 0;
	}
	else
	{
		$i++;
	}
	endwhile;
?>
</table>
<?
}	/// end function

function imagetext()
{
	$tableindex = 1;
?>
<table id="images" align="center">
<?
	while (next_image(false, $firstPageImages))
	{
		if ($tableindex == 1)
		{
			echo "<tr>\n";
		}
?>
	<td class="image">
		<div class="imagethumb"><a href="<?php echo htmlspecialchars(getImageLinkURL());?>" title="<?php echo strip_tags(getImageTitle());?>"><?php printImageThumb(getImageTitle()); ?></a></div>
		<div class="imagetitle"><p><? /*<a href="<?=getImageLinkURL();?>" title="<?=getImageTitle();?>"> */ ?>
		<?php printImageTitle(); ?></a><br/><?php printImageDate(); ?></p></div>
	</td>
<?php
		if ($tableindex == 3)
		{
			echo "</tr>\n";
			$tableindex = 1;
		}
		else
		{
			$tableindex++;
		}
	}
?>
</table>
<?
	return $tableindex-1;
}








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
	if (checkforPassword()) { return false; }
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