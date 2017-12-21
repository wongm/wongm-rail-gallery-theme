<?php

//******************************************************************************
// Miscellaneous functions for the ZenPhoto gallery that I need
//
// For railgeelong.com and wongm.railgeelong.com
//
// V 1.0.0
//
//******************************************************************************

DEFINE ('UNCAPTIONED_IMAGE_REGEX', "i.title REGEXP '_[0-9]{4}' OR i.title REGEXP 'DSCF[0-9]{4}'");
DEFINE ('CAPTIONED_IMAGE_REGEX', "i.title NOT REGEXP '_[0-9]{4}' AND i.title NOT REGEXP 'DSCF[0-9]{4}'");

function pluralNumberWord($number, $text)
{
	if (is_numeric($number))
	{
		if ($number == 0)
		{
			return $number.' '.$text.'s';
		}
		if ($number > 1)
		{
			return $number.' '.$text.'s';
		}
		else
		{
			return "$number $text";
		}
	}
}

/**
 * Returns the url of the previous image.
 *
 * @return string
 */
function getPrevImageTitle() {
	if(!in_context(ZP_IMAGE)) return false;
	global $_zp_current_album, $_zp_current_image;
	$previmg = $_zp_current_image->getPrevImage();
	return $previmg->getTitle();
}

function getNextImageTitle() {
	if(!in_context(ZP_IMAGE)) return false;
	global $_zp_current_album, $_zp_current_image;
	$nextimg = $_zp_current_image->getNextImage();
	return $nextimg->getTitle();
}

/**
 * Prints the exif data of the current image.
 *
 */
function printEXIFData()
{
	global $_zp_current_image;
	$result = getImageMetaData();
	if (function_exists('getRollingHitcounter'))
	{
		$hitCounterText = getRollingHitcounter($_zp_current_image);
	}
	else if (function_exists('formatHitCounter'))
	{
		$hitCounterText = formatHitCounter(incrementAndReturnHitCounter('image'));
	}
	
	if (function_exists('getDeathmatchRatingsText'))
	{
		$ratingsText = getDeathmatchRatingsText();
		
		if (strlen($hitCounterText) > 0 && strlen($ratingsText) > 0)
		{
			$hitCounterText .= "<br/>";
		}
	}

	if ( zp_loggedin() )
	{
		if (strlen($hitCounterText) > 0)
		{
			$hitCounterText .= "<br/>";
		}
		
		$hitCounterText .= "Week reset = ".$_zp_current_image->get('hitcounter_week_reset').", Month reset = ".$_zp_current_image->get('hitcounter_month_reset');

		if ($result['EXIFGPSLatitudeRef'] == 'S')
		{
    		$EXIFGPSLatitudeRef = '-';
		}
		if ($result['EXIFGPSLongitudeRef'] == 'W')
		{
    		$EXIFGPSLongitudeRef = '-';
		}
		
		$maptext = $EXIFGPSLatitudeRef . $result['EXIFGPSLatitude'] . "," . $EXIFGPSLongitudeRef . $result['EXIFGPSLongitude'];
		$mapurl = "https://www.google.com.au/maps/@$maptext,14z";
		$maplink = "<br/><a href=\"$mapurl\" target=\"_blank\">$maptext</a>";
	}
	else
	{
    	$maplink = '';
	}

	if (sizeof($result) > 1 AND $result['EXIFDateTimeOriginal'] != '')
	{
		$date = explode(':', $result['EXIFDateTimeOriginal']);
		$splitdate = explode(' ', $date[2]);
		$udate = mktime($splitdate[1], $date[3],$date[4],$date[1],$splitdate[0],$date[0]);
		$fdate = strftime('%B %d, %Y', $udate);
		$ftime = strftime('%H:%M %p', $udate);

		//check if seach by date exists, should be in set up in plugins\archive_days.php
		if (function_exists('printSingleMonthArchive'))
		{
    		$dateString = $date[0] . '-' . $date[1] . '-' . $splitdate[0];
    		$dateLink = "<a href=\"".html_encode(getSearchURL(null, $dateString, null, 0, null))."\" title=\"See other photos from this date\">$fdate</a> $ftime";
		}
		else
		{
			$dateLink = $fdate.'&nbsp;'.$ftime;
		}
	?>
<p class="exif">
Taken with a <?=$result['EXIFModel'] ?><br/>
Date: <?=$dateLink;?><br/>
Exposure Time: <?=$result['EXIFExposureTime'] ?><br/>
Aperture Value: <?=$result['EXIFFNumber'] ?><br/>
<? if (isset($result['EXIFFocalLength'])) { ?>Focal Length: <?=$result['EXIFFocalLength'] ?><br/><? } ?>
<?=$hitCounterText.$ratingsText.$maplink?>
</p>
<?
	}
	else
	{
?>
<p class="exif">
<?=$hitCounterText.$ratingsText; ?>
</p>
<?
	}	// end if
}		// end function

function drawNewsNextables()
{
	$next = getNextNewsURL();
	$prev = getPrevNewsURL();

	if($next OR $prev) {
	?>
<table class="pagelist"><tr><td>
  <?php if($prev) { ?><a class="prev" href="<?=$prev['link'];?>" title="<?=$prev['title']?>"><span>&laquo;</span> <?=$prev['title']?></a> <? } ?>
  <?php if($next) { ?><a class="next" href="<?=$next['link'];?>" title="<?=$next['title']?>"><?=$next['title']?> <span>&raquo;</span></a> <? } ?>
</td></tr></table>
  <?php }
}

function drawNewsFrontpageNextables()
{
	$next = getNextNewsPageURL();
	$prev = getPrevNewsPageURL();

	if($next OR $prev) {
	?>
<table class="pagelist"><tr><td>
  <?php if($prev) { ?><a class="prev" href="<?="http://".$_SERVER['HTTP_HOST'].$prev;?>" title="Previous page"><span>&laquo;</span> Previous page</a> <? } ?>
  <?php if($next) { ?><a class="next" href="<?="http://".$_SERVER['HTTP_HOST'].$next;?>" title="Next page">Next page <span>&raquo;</span></a> <? } ?>
</td></tr></table>
  <?php }
}

/**
 * Returns the raw title of the current image.
 *
 * @return string
 */
function getImageAlbumLink() {
	if(!in_context(ZP_IMAGE)) return false;
	global $_zp_current_image;
	
	$title = '';
	if (function_exists('getAlbumTitleForPhotostreamImage'))
	{
		$title = getAlbumTitleForPhotostreamImage();
	}
	
	if (strlen($title) == 0)
	{
		$title = $_zp_current_image->getAlbum()->getTitle();
	}
	$folder = getAlbumURL($_zp_current_image->getAlbum());
	return "<p>In album: <a href=\"$folder\">$title</a></p>";
}

function printImageDescWrapped()
{
	if (strlen(getImageDesc()) > 0)
	{
		echo "<p>" . getImageDesc() . "</p>\n";
	}	
}

/*
 *
 * drawWongmGridImages()
 *
 * Draw a grid of images for an album
 * Used by album.php and search.php
 *
 */
function drawWongmGridImages($pageType, $numberOfItems)
{
    $count = 0;
    
	?>
<!-- Images -->
<div id="imagewrapper">
<div id="images">
<?php

    // also enforce limit on items displayed
    while (next_image() && ($count <= $numberOfItems))
    {
        $count++;
        drawWongmImageCell($pageType);
    } ?>
</div>
</div>
<?
}	// end function

function drawWongmImageCell($pageType)
{
	global $_zp_current_image;
	
	$albumLinkText = "";
    if ($pageType != 'album')
    {
        $albumLinkText = getImageAlbumLink();
    }
	
	// if recent uploads and not logged in
	if ($pageType == 'uploads' && !zp_loggedin())
	{
		$hitcounterText = '';
	}
	// ratings instead
	else if ($pageType == 'ratings')
	{
		$hitcounterText = getDeathmatchRatingsText();
	}
	// other types of recent items / most viewed pages
	else
	{
		$hitcounterText = getRollingHitCounter($_zp_current_image, $pageType);
	}
	
	if (strlen($hitcounterText) > 0)
	{
		$hitcounterText = '<br/>' . $hitcounterText;
	}
?>
<div class="image<?=getCssClassForImage()?>">
	<div class="imagethumb"><a href="<?=getImageURL();?>" title="<?=getImageTitle();?>">
		<img src="<? echo getDefaultSizedImage() ?>" title="<?=getImageTitle();?>" alt="<?=getImageTitle();?>" />
	</a></div>
	<div class="imagetitle">
		<h4><a href="<?=getImageURL();?>" title="<?=getImageTitle();?>"><?php printImageTitle(); ?></a></h4>
		<?php echo printImageDescWrapped(); ?>
		<small><?php printImageDate(); ?><?php echo $hitcounterText ?></small>
		<?php echo $albumLinkText ?>
	</div>
</div>
<?php
}

function getCssClassForImage()
{
    if (!zp_loggedin())
		return;

	global $_zp_current_image;
	
	$width = $_zp_current_image->getWidth();
	$height = $_zp_current_image->getHeight();

	if (($width == 1024 && $height == 683)
	    || ($width == 683 && $height == 1024)
	    || ($width == 1920 && $height == 1280)
	    || ($width == 1280 && $height == 1920))
	{
        return;
	}
	
	$aspectratio = ($width / $height);
	if ($height > $width) 
	{
    	$aspectratio = ($height / $width);
	}
	
	if ($aspectratio <= 1.499)
	{
    	return " aspectratioerrorshrink $aspectratio";
	}
	
	return " aspectratioerrorexpand $aspectratio";
}

/*
 *
 * drawIndexAlbums()
 *
 * Draw a list of albums,
 * thumbnail image on the left, details on the right
 * Used by recent-albums.php (recent albums) and everything.php (all albums)
 *
 */
function drawIndexAlbums($type=null, $numberOfItems = null)
{
	global $_zp_current_album;

	echo "<div id=\"indexalbums\">\n";

	if ($type == 'dynamiconly')
	{
		while (next_album(true))
		{
			if ($_zp_current_album->isDynamic())
			{
				drawWongmAlbumRow();
			}
		}
	}
	elseif($type=='nodynamic')
	{
		while (next_non_dynamic_album())
		{
			if (!$_zp_current_album->isDynamic())
			{
				drawWongmAlbumRow();
			}
		}
	}
	elseif($type=='recent')
	{
    	$totalDisplayed = 0;
    	
		while (next_non_dynamic_album(false, 'ID', 'DESC'))
		{
			if (!$_zp_current_album->isDynamic() && $totalDisplayed < getOption('wongm_recentalbum_count'))
			{
    			$totalDisplayed++;
    			drawWongmAlbumRow();
			}
		}
	}
	else
	{
		while (next_album())
		{
			drawWongmAlbumRow();
			
            // enforce limit on items displayed
    		$count++;
            if ($numberOfItems!= null && $count >= $numberOfItems)
            {
                break;
            }
		}
	}
 ?>
</div>
<?
}

function drawWongmListSubalbums()
{
	$toReturn = 0;
	
	if (getNumAlbums() > 0)
	{
?>
<!-- Sub-Albums -->
<div id="indexalbums">
<?php
	while (next_album()):
		drawWongmAlbumRow();
		$toReturn++;
	endwhile;
?>
</div>
<?
	}
	
	return $toReturn;
	
}	/// end function

/*
 *
 * drawWongmAlbumRow()
 *
 * Draw an album row
 * thumbnail image on the left, details on the right
 * Used by drawIndexAlbums() in this file
 *
 */
function drawWongmAlbumRow($type = "")
{
	global $_zp_current_album;
?>
<div class="album">
	<div class="albumthumb">
		<a href="<?php echo htmlspecialchars(getAlbumURL());?>" title="<?php echo gettext('View album:'); ?><?php echo strip_tags(getAlbumTitle());?>">
			<?php printSizedAlbumThumbImage(getAlbumTitle()); ?>
		</a>
	</div>
	<div class="albumdesc">
		<h4><a href="<?php echo htmlspecialchars(getAlbumURL());?>" title="<?php echo gettext('View album:'); ?><?php echo strip_tags(getAlbumTitle());?>">
		<?php printAlbumTitle(); ?></a></h4>
		<?php if ($type != 'frontpage') { ?><p><small><?php printAlbumDate("", "%B %d, %Y"); ?><?php if (zp_loggedin() && function_exists('printRollingHitcounter')) { printRollingHitcounter($_zp_current_album, true); } ?></small></p><?php } ?>
		<p><?php printAlbumDesc(); ?></p>
<? 	if (zp_loggedin())
	{
		echo "<p>";
		echo printLinkHTML('/zp-core/admin-edit.php?page=edit&album=' . urlencode(getAlbumURL()), gettext("Edit details"), NULL, NULL, NULL);
		echo '</p>';
	}
?>
	</div>
</div>
<?

}	// end function

function replace_filename_with_cache_thumbnail_version($filename)
{
    $thumb_size = getOption('image_size');
    
    // gotcha - cached thumbnails always have lower case file extension
	$imgURL = str_replace('.jpg', '_' . $thumb_size . '.jpg', $filename);
	$imgURL = str_replace('.JPG', '_' . $thumb_size . '.jpg', $imgURL);
	$imgURL = str_replace('.gif', '_' . $thumb_size . '.gif', $imgURL);
	$imgURL = str_replace('.GIF', '_' . $thumb_size . '.gif', $imgURL);
	$imgURL = str_replace('.png', '_' . $thumb_size . '.png', $imgURL);
	$imgURL = str_replace('.PNG', '_' . $thumb_size . '.png', $imgURL);
	$imgURL = str_replace('.jpeg', '_' . $thumb_size . '.jpeg', $imgURL);
	$imgURL = str_replace('.JPEG', '_' . $thumb_size . '.jpeg', $imgURL);
	return $imgURL;	
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
		$_zp_current_album = newAlbum(array_shift($_zp_albums), true, true);
		save_context();
		add_context(ZP_ALBUM);
		return true;
	} else if (empty($_zp_albums)) {
		$_zp_albums = NULL;
		$_zp_current_album = $_zp_current_album_restore;
		restore_context();
		return false;
	} else {
		$_zp_current_album = newAlbum(array_shift($_zp_albums), true, true);
		return true;
	}
}

function printMWEditableImageTitle($editable=false, $editclass='editable imageTitleEditable', $messageIfEmpty = true ) {
	if ( $messageIfEmpty === true ) {
		$messageIfEmpty = gettext('(No title...)');
	}
	printMWEditable('image', 'title', $editable, $editclass, $messageIfEmpty);
}

function printMWEditableImageDesc($editable=false, $editclass='', $messageIfEmpty = true) {
	if ( $messageIfEmpty === true ) {
		$messageIfEmpty = gettext('(No description...)');
	}
	printMWEditable('image', 'desc', $editable, $editclass, $messageIfEmpty, !getOption('tinyMCEPresent'));
}

function printMWEditableAlbumTitle($editable=false, $editclass='', $messageIfEmpty = true) {
	if ( $messageIfEmpty === true ) {
		$messageIfEmpty = gettext('(No title...)');
	}
	printMWEditable('album', 'title', $editable, $editclass, $messageIfEmpty);
}

function printMWEditableAlbumDesc($editable=false, $editclass='', $messageIfEmpty = true ) {
	if ( $messageIfEmpty === true ) {
		$messageIfEmpty = gettext('(No description...)');
	}
	printMWEditable('album', 'desc', $editable, $editclass, $messageIfEmpty, !getOption('tinyMCEPresent'));
}

/**
 * Print any album or image data and make it editable in place
 *
 * @param string $context	either 'image' or 'album'
 * @param string $field		the data field to echo & edit if applicable: 'date', 'title', 'place', 'description', ...
 * @param bool   $editable 	when true, enables AJAX editing in place
 * @param string $editclass CSS class applied to element if editable
 * @param mixed  $messageIfEmpty message echoed if no value to print
 * @param bool   $convertBR	when true, converts new line characters into HTML line breaks
 * @param string $override	if not empty, print this string instead of fetching field value from database
 * @param string $label "label" text to print if the field is not empty
 * @since 1.3
 * @author Ozh
 */
function printMWEditable($context, $field, $editable = false, $editclass = 'editable', $messageIfEmpty = true, $convertBR = false, $override = false, $label='') {
	switch($context) {
		case 'image':
			global $_zp_current_image;
			$object = $_zp_current_image;
			break;
		case 'album':
			global $_zp_current_album;
			$object = $_zp_current_album;
			break;
		case 'pages':
			global $_zp_current_zenpage_page;
			$object = $_zp_current_zenpage_page;
			break;
		case 'news':
			global $_zp_current_zenpage_news;
			$object = $_zp_current_zenpage_news;
			break;
		default:
			trigger_error(gettext('printMWEditable() incomplete function call.'), E_USER_NOTICE);
			return false;
	}
	if (!$field || !is_object($object)) {
		trigger_error(gettext('printMWEditable() invalid function call.'), E_USER_NOTICE);
		return false;
	}
	$text = trim( $override !== false ? $override : get_language_string($object->get($field)) );
	$text = zp_apply_filter('front-end_edit', $text, $object, $context, $field);
	if ($convertBR) {
		$text = str_replace("\r\n", "\n", $text);
		$text = str_replace("\n", "<br />", $text);
	}

	if (empty($text)) {
		if ( $editable && zp_loggedin() ) {
			if ( $messageIfEmpty === true ) {
				$text = gettext('(...)');
			} elseif ( is_string($messageIfEmpty) ) {
				$text = $messageIfEmpty;
			}
		}
	}
	if (!empty($text)) echo $label;
	if ($editable && getOption('edit_in_place') && zp_loggedin()) {
		// Increment a variable to make sure all elements will have a distinct HTML id
		static $id = 1;
		$id++;
		$class= 'class="' . trim("$editclass zp_editable zp_editable_{$context}_{$field}") . '"';
		echo "<span id=\"editable_{$context}_$id\" $class>" . $text . "</span>\n";
		echo "<script type=\"text/javascript\">editInPlace('editable_{$context}_$id', '$context', '$field');</script>";
	} else {
		$class= 'class="' . "zp_uneditable zp_uneditable_{$context}_{$field}" . '"';
		echo "<span $class>" . $text . "</span>\n";
	}
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
		$date = str_replace("/", "", $date);
		if (empty($date)) { return ""; }
		if ($date == '0000-00') { return gettext("no date"); };

		if (sizeof(explode('-', $date)) == 3) {
			$format='F d, Y';
		}

		$dt = strtotime($date."-01");
		return date($format, $dt);
	}
	return false;
}

function printMetadata($pageTitle)
{
	$description = "Photographs of trains and railway infrastructure from around Victoria, Australia";
	$title = "";
	
	// if date based search with images - we can get summary data for the current date
	if (in_context(ZP_SEARCH))
	{
		// check for images, and that we are not on a month based archive page
		if (isset($_REQUEST['date']) && strlen($_REQUEST['date']) > 8 && getNumImages() && strlen($_REQUEST['date']) > 7)
		{
			global $_zp_current_DailySummaryItem;
			$_zp_current_DailySummaryItem = new DailySummaryItem($_REQUEST['date']);			
			$description = getDailySummaryDesc();
			$title = getDailySummaryTitle();
			$imagePath = $_zp_current_DailySummaryItem->getDailySummaryThumbImage()->getSizedImage(getOption('image_size'));
		}
	}
	// image page
	else if (in_context(ZP_IMAGE))
	{
		$imagePath = getDefaultSizedImage();
		if (strlen(getImageDesc()) > 0) {
			$description = strip_tags(getImageDesc());
		}
		$title = getImageTitle();
	} 
	// album page
	else if (in_context(ZP_ALBUM))
	{
		global $_zp_current_album;
		
		// makeImageCurrent can change $_zp_current_album variable to child album
		// save a local copy, so we can get THIS album back later
		$currentAlbum = $_zp_current_album;
		$currentContext = get_context();
		makeImageCurrent($_zp_current_album->getAlbumThumbImage());
		$_zp_current_album = $currentAlbum;
		set_context($currentContext);
		
		$imagePath = getDefaultSizedImage();
		
		if (strlen(getAlbumDesc()) > 0) {
			$description = strip_tags(getAlbumDesc());
		}
		$title = getBareAlbumTitle();
	}
	
	echo "<meta property=\"og:description\" content=\"$description\" />\n";
	
	if (strlen($title) > 0)
	{
		$protocol = SERVER_PROTOCOL;
		if ($protocol == 'https_admin') {
			$protocol = 'https';
		}
		$imagePath = $protocol . '://' . $_SERVER['HTTP_HOST'] . WEBPATH . $imagePath;
		
		echo "<meta property=\"og:image\" content=\"$imagePath\" />\n";
		echo "<meta property=\"og:title\" content=\"$title\" />\n";	
		echo "<meta name=\"twitter:card\" content=\"photo\">\n";
		echo "<meta name=\"twitter:title\" content=\"$title\">\n";
		echo "<meta name=\"twitter:image:src\" content=\"$imagePath\">\n";
	}
	else{
		echo "<meta name=\"twitter:card\" content=\"summary\" />\n";
		echo "<meta name=\"twitter:title\" content=\"" . $pageTitle . "\">\n";
	}
	
	echo "<meta name=\"twitter:site\" content=\"@wongmsrailpics\">\n";
	echo "<meta name=\"twitter:creator\" content=\"@aussiewongm\">\n";
	echo "<meta name=\"twitter:domain\" content=\"" . getGalleryTitle() . "\">\n";
	echo "<meta name=\"twitter:description\" content=\"$description\">\n";	
	echo "<meta name=\"description\" content=\"$description\" />\n";
}

function buildGalleryImageAlbumCountMessage()
{
    global $totalGalleryImageCount, $totalGalleryAlbumCount;
    
    $albumsArray = query_single_row("SELECT count(*) FROM ".prefix('albums'));
    $totalGalleryAlbumCount = number_format(array_shift($albumsArray), 0, '.', ',');
    
    $photosArray = query_single_row("SELECT count(*) FROM ".prefix('images'));
    $totalGalleryImageCount = number_format(array_shift($photosArray), 0, '.', ',');
    
    return "$totalGalleryImageCount images in $totalGalleryAlbumCount albums.";
}

// based on printAlbumThumbImage
function printSizedAlbumThumbImage($alt)
{
	global $_zp_current_album;
	$thumbobj = $_zp_current_album->getAlbumThumbImage();
	$html = '<img src="' . html_encode(pathurlencode($thumbobj->getSizedImage(getOption('image_size')))) . '" alt="' . html_encode($alt) . '" />';
	$html = zp_apply_filter('standard_album_thumb_html', $html);
	echo $html;
	
}

?>
