<?php

//******************************************************************************
// Miscellaneous functions for the ZenPhoto gallery that I need
//
// For railgeelong.com and wongm.railgeelong.com
//
// V 1.0.0
//
//******************************************************************************

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


/*
 *
 * drawWongmGridSubalbums()
 *
 * Draw a grid of sub-albums for an album
 *
 */
function drawWongmGridAlbums($numberOfItems)
{
?>
<!-- Sub-Albums -->
<table class="centeredTable">
<?php
	// neater for when only 4 items
	if ($numberOfItems == 4)
	{
		$i = 1;
	}
	else
	{
		$i = 0;
	}
	while (next_album()):
    	$count++;
    	if ($i == 0)
    	{
    		echo '<tr>';
    	}
    	global $_zp_current_album;
?>
<td class="album" valign="top">
	<div class="albumthumb"><a href="<?=getAlbumURL();?>" title="<?=getAlbumTitle();?>">
	<?php printAlbumThumbImage(getAlbumTitle()); ?></a></div>
	<div class="albumtitle"><h4><a href="<?=getAlbumURL();?>" title="<?=getAlbumTitle();?>">
	<?php printAlbumTitle(); ?></a></h4><small><?php printAlbumDate(); ?><?php if (zp_loggedin() && function_exists('printRollingHitcounter')) { printRollingHitcounter($_zp_current_album, true); } ?></small></div>
	<div class="albumdesc"><?php printAlbumDesc(); ?></div>
</td>
<?php
    	if ($i == 2)
    	{
    		echo "</tr>\n";
    		$i = 0;
    	}
    	else
    	{
    		$i++;
    	}
    	 
        // enforce limit on items displayed
        if ($count >= $numberOfItems)
        {
            break;
        }
    
	endwhile;
?>
</table>
<?
}	/// end function



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
function drawWongmGridImages($numberOfItems)
{
    $albumLinkHtml = "";
    $column = 0;
    $count = 0;
    
	?>
<!-- Images -->
<table class="centeredTable">
<?php
  // neater for when only 4 items
    if ($numberOfItems != 4)
    {
        $row = 0;
        $style = ' class="trio"';
    }

    // also enforce limit on items displayed
    while (next_image() && ($count <= $numberOfItems))
    {
        $column++;
        $count++;
        
        if ($row == 0)
        {
            echo "<tr$style>\n";
        }
        
        if (in_context(ZP_SEARCH))
        {
            $albumLinkHtml = getImageAlbumLink();
        }
        
        global $_zp_current_image;
?>
<td class="image">
	<div class="imagethumb"><a href="<?=getImageURL();?>" title="<?=getImageTitle();?>">
		<img src="<? echo getImageThumb() ?>" title="<?=getImageTitle();?>" alt="<?=getImageTitle();?>" />
	</a></div>
	<div class="imagetitle">
		<h4><a href="<?=getImageURL();?>" title="<?=getImageTitle();?>"><?php printImageTitle(); ?></a></h4>
		<?php echo printImageDescWrapped(); ?>
		<small><?php printImageDate(); ?><?php if (zp_loggedin() && function_exists('printRollingHitcounter')) { printRollingHitcounter($_zp_current_image, true); } ?></small><?php echo $albumLinkHtml; ?>
	</div>
</td>
<?php
        if ($row == 2 || ($numberOfItems == 4 && $row == 1))
        {
            echo "</tr>\n";
            $row = 0;
        }
        else
        {
            $row++;
        }
    } ?>
</table>
<?
}	// end function

/*
 *
 * drawIndexAlbums()
 *
 * Draw a list of albums,
 * thumbnail image on the left, details on the right
 * Used by recent-albums.php (recent albums) and everything.php (all albums)
 *
 */
function drawIndexAlbums($type=null, $site=null)
{
	global $_zp_current_album;

	echo "<table id=\"centeredAlbums\" class=\"indexalbums\">\n";

	if ($type == 'dynamiconly' OR $type == 'frontpage')
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
		}
	}
 ?>
</table>
<?
}

/*
 *
 * drawWongmAlbumRow()
 *
 * Draw an album row
 * thumbnail image on the left, details on the right
 * Used by drawIndexAlbums() in this file
 *
 */
function drawWongmAlbumRow()
{
	global $_zp_current_album;
?>
<tr class="album">
	<td class="albumthumb">
		<a href="<?php echo htmlspecialchars(getAlbumURL());?>" title="<?php echo gettext('View album:'); ?> <?php echo strip_tags(getAlbumTitle());?>"><?php printAlbumThumbImage(getAlbumTitle()); ?></a>
	</td><td class="albumdesc">
		<h4><a href="<?php echo htmlspecialchars(getAlbumURL());?>" title="<?php echo gettext('View album:'); ?> <?php echo strip_tags(getAlbumTitle());?>"><?php printAlbumTitle(); ?></a></h4>
		<p><small><?php printAlbumDate("", "%B %d, %Y"); ?><?php if (zp_loggedin() && function_exists('printRollingHitcounter')) { printRollingHitcounter($_zp_current_album, true); } ?></small></p>
		<p><?php printAlbumDesc(); ?></p>
<? 	if (zp_loggedin())
	{
		echo "<p>";
		echo printLinkHTML('/zp-core/admin-edit.php?page=edit&album=' . urlencode(getAlbumURL()), gettext("Edit details"), NULL, NULL, NULL);
		echo '</p>';
	}
?>
	</td>
</tr>
<?

}	// end function

function replace_filename_with_cache_thumbnail_version($filename)
{
    $thumb_size = getOption('thumb_size');
    
    // gotcha - cached thumbnails always have lower case file extension
	$imgURL = str_replace('.jpg', '_' . $thumb_size . '_thumb.jpg', $filename);
	$imgURL = str_replace('.JPG', '_' . $thumb_size . '_thumb.jpg', $imgURL);
	$imgURL = str_replace('.gif', '_' . $thumb_size . '_thumb.gif', $imgURL);
	$imgURL = str_replace('.GIF', '_' . $thumb_size . '_thumb.gif', $imgURL);
	$imgURL = str_replace('.png', '_' . $thumb_size . '_thumb.png', $imgURL);
	$imgURL = str_replace('.PNG', '_' . $thumb_size . '_thumb.png', $imgURL);
	$imgURL = str_replace('.jpeg', '_' . $thumb_size . '_thumb.jpeg', $imgURL);
	$imgURL = str_replace('.JPEG', '_' . $thumb_size . '_thumb.jpeg', $imgURL);
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

function buildGalleryImageAlbumCountMessage()
{
    $albumsArray = query_single_row("SELECT count(*) FROM ".prefix('albums'));
    $albumNumber = number_format(array_shift($albumsArray), 0, '.', ',');
    
    $photosArray = query_single_row("SELECT count(*) FROM ".prefix('images'));
    $photosNumber = number_format(array_shift($photosArray), 0, '.', ',');
    
    return "$photosNumber images in $albumNumber albums.";
}

?>