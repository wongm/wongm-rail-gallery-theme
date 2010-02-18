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

function getNumberCurrentDispayedRecords($maxRecordsPerPage,$numberOfRecords,$searchPageNumber)
{
	if ($numberOfRecords != $totalNumberOfRecords)
	{
		$lowerBound = ($maxRecordsPerPage*$searchPageNumber)+1;
		$upperBound = $lowerBound+$numberOfRecords-1;
		$extraBit = "$lowerBound to $upperBound shown on this page";
	}
	return $extraBit;
}

function getFullImageLinkURL() { 
	if(!in_context(ZP_IMAGE)) return false;
  	global $_zp_current_image;
  		
	if ($_REQUEST['p'] == 'full')
	{
    	return rewrite_path('/' . pathurlencode($_zp_current_image->album->name) . '/' . urlencode($_zp_current_image->filename) . im_suffix(),
			'/index.php?album=' . urlencode($_zp_current_image->album->name) . '&image=' . urlencode($_zp_current_image->filename));
	}
	else
	{
	  	return rewrite_path('/' . pathurlencode($_zp_current_image->album->name) . '/' . urlencode($_zp_current_image->filename) . im_suffix() . '?p=full',
	    	'/index.php?album=' . urlencode($_zp_current_album->name) . '&image=' . urlencode($_zp_current_image->name));
    }
}

function drawRandomPage()
{
	echo "<table class=\"centeredTable\">";
	$i=0;
	$j=0;
	
	while ($i < MAXIMAGES_PERRANDOM)
	{
		echo "<tr>";
		
		while ($j < 3)
		{
			$randomImage = getRandomImages();
			$randomImageURL = getURL($randomImage);
			$photoTitle = $randomImage->getTitle();
			$photoDate = strftime(TIME_FORMAT, strtotime($randomImage->getDateTime()));
			$imageCode = "<img src='".$randomImage->getThumb()."' alt='".$photoTitle."'>";
			
			$albumForPhoto = $randomImage->getAlbum();
			$photoAlbumTitle = $albumForPhoto->getTitle();
			$photoPath = $albumForPhoto->getAlbumLink();
					
			if ($photoDesc == '')
			{
				$photoDesc = $photoTitle;
			}
			else
			{
				$photoDesc = 'Description: '.$photoDesc;
			}
?>
<td class="i" width="33%"><a href="http://<?=$_SERVER['HTTP_HOST'].$randomImageURL?>"><?=$imageCode?></a>
	<h4><a href="http://<?=$_SERVER['HTTP_HOST'].$randomImageURL?>"><?=$photoTitle; ?></a></h4>
	<small><?=$photoDate?><? printHitCounter($randomImage); ?></small><br/>
	In Album: <a href="http://<?=$_SERVER['HTTP_HOST'].$photoPath; ?>"><?=$photoAlbumTitle; ?></a>
</td>
<?
			$j++;
			$i++;
		}	//end while for cols
		$j=0;
		echo "</tr>";
	}	//end while for rows
	
	echo "</table>";
}	

/*
function getRecentImageLink()
{
	return "<h1>HOO</h1>";

	$recentSQL = "SELECT " . prefix('images') . ".filename, " . prefix('images') . ".date, " . prefix('albums') . ".folder 
		FROM " . prefix('images') . ", " . prefix('albums') . " 
		WHERE zen_images.albumid = zen_albums.id 
		ORDER BY zen_images.id DESC LIMIT 0 , 1";
	$lastImage = query_full_array($recentSQL);
	$recenturl = "http://".$_SERVER['HTTP_HOST'].'/'.$lastImage[0]['folder'].
		'/image/'.getOption('thumb_size').'/'.$lastImage[0]['filename'];		
	return "<img src=\"$recenturl\" title=\"Recent Uploads\" alt=\"Recent Uploads\" />";
}
*/

function getMostRecentImageDate()
{
	global $mostRecentImageDate;
	
	// only get if not cached
	if ($mostRecentImageDate == '')
	{
		$recentSQL = "SELECT " . prefix('images') . ".date FROM " . prefix('images') . " 
			ORDER BY " . prefix('images') . ".date DESC LIMIT 0 , 1";
		$lastImage = query_full_array($recentSQL);
		$mostRecentImageDate = strftime(TIME_FORMAT, strtotime($lastImage[0]['date']));
	}
	
	return $mostRecentImageDate;
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

function getImageEXIFData()
{
	return getImageMetaData();
}


/**
 * Prints the exif data of the current image.
 *
 */
function printEXIFData() 
{
	global $_zp_current_image;
	$result = getImageEXIFData();
	$hitCounterText = formatHitCounter(incrementAndReturnHitCounter('image'));
	$ratingsText = formatRatingCounter(array(
		$_zp_current_image->get('ratings_win'), 
		$_zp_current_image->get('ratings_view'),
		$_zp_current_image->get('ratings_score')
		));
		
	if ( zp_loggedin() )
	{
		$hitCounterText .= "<br>Week reset = ".$_zp_current_image->get('hitcounter_week_reset').", Month reset = ".$_zp_current_image->get('hitcounter_month_reset');
	}
	
	if (sizeof($result) > 1 AND $result[EXIFDateTimeOriginal] != '')
	{
		$date = split(':', $result[EXIFDateTimeOriginal]);
		$splitdate = split(' ', $date[2]);
		$udate = mktime($splitdate[1], $date[3],$date[4],$date[1],$splitdate[0],$date[0]);
		$fdate = strftime('%B %d, %Y', $udate);
		$ftime = strftime('%H:%M %p', $udate);
			
		if (DATE_SEARCH)
		{
			$dateLink = "<a href=\"".SEARCH_URL_PATH."/archive/$date[0]-$date[1]-$splitdate[0]\" alt=\"See other photos from this date\" title=\"See other photos from this date\">$fdate</a> $ftime";
		}
		else
		{
			$dateLink = $fdate.'&nbsp;'.$ftime;
		}
	?>
<p class="exif">
Taken with a <?=$result[EXIFModel] ?><br/>
Date: <?=$dateLink;?><br/>
Exposure Time: <?=$result[EXIFExposureTime] ?><br/>
Aperture Value: <?=$result[EXIFFNumber] ?><br/>
Focal Length: <?=$result[EXIFFocalLength] ?>
<?=$hitCounterText.$ratingsText?>
</p>
<?	
	}
	else
	{
?>
<p class="exif">
<?=str_replace('<br>','',$hitCounterText.$ratingsText) ?>
</p>	
<?	
	}	// end if
}		// end function

/**
 * Prints a list of all pages.
 *
 * @param string $class the css class to use, "pagelist" by default
 * @param string $id the css id to use
 */
function drawGalleryPageNumberLinks($url='')
{
	$total = getTotalPages();
	$current = getCurrentPage();
	
	echo '<p>';
  
  	if ($total > 0)
  	{
		echo 'Page: ';
	}
	
	if ($current > 3 AND $total > 7)
	{
		echo "\n <a href=\"".$url.getPageURL(1)."\" alt=\"First page\" title=\"First page\">1</a>&nbsp;"; 
		
		if ($current > 4)
		{
			echo "...&nbsp;";
		}
	}
	
	for ($i=($j=max(1, min($current-2, $total-6))); $i <= min($total, $j+6); $i++) 
	{
		if ($i == $current)
		{
			echo $i;
		}
		else
		{
			echo '<a href="'.$url.getPageURL($i).'"\" alt="Page '.$i.'" title="Page '.$i.'">'.($i).'</a>';
		}
		echo "&nbsp;";
	}
	if ($i <= $total) 
	{
		if ($current < $total-5)
		{
			echo "...&nbsp;";
		}
		
		echo "<a href=\"".$url.getPageURL($total)."\" alt=\"Last page\" title=\"Last page\">" . $total . "</a>"; 
	}
	echo '</p>';
}

/**
 * Returns the title of the current image.
 * Truncates it if over given length
 *
 * @param bool $editable if set to true and the admin is logged in allows editing of the title
 */
function printTruncatedImageTitle($editable=false) {
	global $_zp_current_image;
	
	if ($editable && zp_loggedin())
	{
		// Increment a variable to make sure all elements will have a distinct HTML id
		static $id = 1;
		$id++;
		$class= 'class="' . trim("zp_editable zp_editable_image_title") . '"';
		echo "<span id=\"editable_image_$id\">" . getImageTitle() . "</span>\n";
		echo "<script type=\"text/javascript\">editInPlace('editable_image_$id', 'image', 'title');</script>";
	}
	else 
	{
		$imageTitle = getImageTitle();
		
		if (strlen($imageTitle) > IMAGETITLE_TRUNCATE_LENGTH)
		{
			//$imageTitle = "<abbr title=\"$imageTitle\">" . substr($imageTitle, 0, IMAGETITLE_TRUNCATE_LENGTH) . "</abbr>...";
			$imageTitle = substr($imageTitle, 0, IMAGETITLE_TRUNCATE_LENGTH) . "...";
		}
		echo "<span id=\"imageTitle\" style=\"display: inline;\">" . $imageTitle . "</span>\n";
	}
}

function drawNewsNextables()
{
	$next = getNextNewsURL();
	$prev = getPrevNewsURL();
		
	if($next OR $prev) {	
	?>  
<table class="nextables"><tr><td>
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
<table class="nextables"><tr><td>
  <?php if($prev) { ?><a class="prev" href="<?="http://".$_SERVER['HTTP_HOST'].$prev;?>" title="Previous page"><span>&laquo;</span> Previous page</a> <? } ?>
  <?php if($next) { ?><a class="next" href="<?="http://".$_SERVER['HTTP_HOST'].$next;?>" title="Next page">Next page <span>&raquo;</span></a> <? } ?>
</td></tr></table>
  <?php } 
}

function drawWongmImageNextables()
{
	if (hasPrevImage() OR hasNextImage())
	{
?>
<table class="nextables"><tr><td>
    <?php if (hasPrevImage()) { ?> <a class="prev" href="<?=getPrevImageURL();?>" title="Previous Image"><span>&laquo;</span> Previous</a> <?php } ?>
    <?php if (hasNextImage()) { ?> <a class="next" href="<?=getNextImageURL();?>" title="Next Image">Next <span>&raquo;</span></a><?php } ?>
</td></tr></table>
<?
	}
}
	
function drawWongmAlbumNextables($showpagelist)
{
  	if (hasPrevPage() || hasNextPage())
  	{
?>
<table class="nextables"><tr><td>
	<?php if (hasPrevPage()) { ?> <a class="prev" href="<?=getPrevPageURL();?>" title="Previous Page"><span>&laquo;</span> Previous</a> <?php } ?>
	<?php if (hasNextPage()) { ?> <a class="next" href="<?=getNextPageURL();?>" title="Next Page">Next <span>&raquo;</span></a><?php } ?>
</td></tr></table>
<?php 
		if ($showpagelist)
		{ 
?>
<div class="pages">
<?  drawGalleryPageNumberLinks();  ?>
</div>
<?		}
	}  	
}

function printForumLink() {

	if (zp_loggedin()) {
		global $_zp_current_image;
		$path = str_replace($_zp_current_image->filename, '', $_zp_current_image->webpath);
		$path = str_replace('albums/', '', $path);
		$textPlain = "\n[url=http://".$_SERVER['HTTP_HOST'].getImageLinkURL( )."]".
		"\n[img]http://".$_SERVER['HTTP_HOST'].$path.'image/'.FORUM_IMAGE_SIZE.'/'.$_zp_current_image->filename."[/img][/url]";
		$text = $_zp_current_image->getTitle().$textPlain;
		$textFull = $_zp_current_image->getTitle()."\n[url=http://".$_SERVER['HTTP_HOST'].getImageLinkURL( )."?p=full]".
		"\n[img]http://".$_SERVER['HTTP_HOST'].$path.'image/'.FORUM_IMAGE_SIZE.'/'.$_zp_current_image->filename."[/img][/url]"
		
?>		<script type="text/javascript">
		function SelectAll(id)
		{
    		document.getElementById(id).focus();
    		document.getElementById(id).select();
		}
		</script>
<?		
		echo '<p>Forum links: </p>';
		echo '<textarea name="forumplain" id="forumplain" cols="100" rows="1" onClick="SelectAll(\'forumplain\')">'.$textPlain.'</textarea>';
		echo '<textarea name="forum" id="forum" cols="100" rows="2" onClick="SelectAll(\'forum\')">'.$text.'</textarea>';
		echo '<textarea name="forumfull" id="forumfull" cols="100" rows="2" onClick="SelectAll(\'forumfull\')">'.$textFull.'</textarea>';
		return true;
	}

	return false;
}

// MW edit
function getSelectedSizedThingy()
{
	if ($_REQUEST['p'] == 'full')
	{
	  	echo 'Click to fit photo to screen';
	}
	else
	{
		echo 'View full size photo ('.getFullWidth().'px by '.getFullHeight().'px)';
	}
}

function drawWongmAlbumRow()
{
	global $_zp_current_album;
?>
<tr class="album">
	<td class="albumthumb">
		<a href="<?php echo htmlspecialchars(getAlbumLinkURL());?>" title="<?php echo gettext('View album:'); ?> <?php echo strip_tags(getAlbumTitle());?>"><?php printAlbumThumbImage(getAlbumTitle()); ?></a>
	</td><td class="albumdesc">
		<h4><a href="<?php echo htmlspecialchars(getAlbumLinkURL());?>" title="<?php echo gettext('View album:'); ?> <?php echo strip_tags(getAlbumTitle());?>"><?php printAlbumTitle(); ?></a></h4>
		<p><small><?php printAlbumDate(""); printHitCounter($_zp_current_album); ?></small></p>
		<p><?php printAlbumDesc(); ?></p>
<? 	if (zp_loggedin())
	{
		echo "<p>";
		echo printLink($zf . '/zp-core/admin-edit.php?page=edit&album=' . urlencode(getAlbumLinkURL()), gettext("Edit details"), NULL, NULL, NULL);
		echo '</p>';
	}		
?>
	</td>

</tr>
<?

}	// end function					


function drawWongmGridSubalbums()
{
?>
<!-- Sub-Albums -->
<table class="centeredTable">
<?php 
	// neater for when only 4 items
	if (getNumSubAlbums() == 4)
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
	global $_zp_current_album;
?>
<td class="album" valign="top">
	<div class="albumthumb"><a href="<?=getAlbumLinkURL();?>" title="<?=getAlbumTitle();?>">
	<?php printAlbumThumbImage(getAlbumTitle()); ?></a></div>
	<div class="albumtitle"><h4><a href="<?=getAlbumLinkURL();?>" title="<?=getAlbumTitle();?>">
	<?php printAlbumTitle(); ?></a></h4><small><?php printAlbumDate(); printHitCounter($_zp_current_album); ?></small></div>
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
	endwhile; 
?>
</table>
<?	
}	/// end function

function drawWongmGridImages()
{
	?>
<!-- Images -->
<table class="centeredTable">
<?php 
  // neater for when only 4 items
  if ($num == 4)
  {
	  $i = 1;
  }
  else
  {
	  $i = 0;
	  $style = 'width="33%" ';
  }
  
  while (next_image()): $c++;
  if ($i == 0)
  {
	  echo '<tr>';
  } 
  
  if (in_context(ZP_SEARCH))
  {
	  $albumlink = getImageAlbumLink();
  }
  
  global $_zp_current_image;
?>
<td class="image" <?=$style?>valign="top">
	<div class="imagethumb"><a href="<?=getImageLinkURL();?>" title="<?=getImageTitle();?>">
	<?php printImageThumb(getImageTitle()); ?></a></div>
	<div class="imagetitle"><h4><a href="<?=getImageLinkURL();?>" title="<?=getImageTitle();?>">
	<?php printImageTitle(); ?></a></h4><small><?php printImageDate(); printHitCounter($_zp_current_image); ?></small><?= $albumlink?></div>
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
  endwhile; ?>
</table>
<?
  return $c;
}	// end function

//******************************************************************************
//*** END MW EDITS *******************************************************
//******************************************************************************



/**
 * Prints the clickable drop down toolbox on any theme page with generic admin helpers
 * @param string $context index, album, image or search
 * @param string $id the html/css theming id
 * @since 1.1
 */
function printMyAdminToolbox($context=null, $id='admin') 
{
	global $_zp_current_album, $_zp_current_image, $_zp_current_search, $_zp_loggedin, $_zp_gallery_page;
	if (zp_loggedin()) {
		$zf = WEBPATH."/".ZENFOLDER;
		$dataid = $id . '_data';
		$page = getCurrentPage();
		$redirect = '';
		?>
		<script type="text/javascript">
			function newAlbum(folder,albumtab) {
				var album = prompt('<?php echo gettext('New album name?'); ?>', '<?php echo gettext('new album'); ?>');
				if (album) {
					window.location = '<?php echo $zf; ?>/admin-edit.php?action=newalbum&album='+encodeURIComponent(folder)+'&name='+encodeURIComponent(album)+'&albumtab='+albumtab;
				}
			}
		</script>
		<?php
		echo '<div id="' .$id. '">'."\n".'<h3><a href="javascript: toggle('. "'" .$dataid."'".');">'.gettext('Admin Toolbox').'</a></h3>'."\n"."\n</div>";
		echo '<div id="' .$dataid. '" style="display: none;">'."\n";
		
		// open the list--all links go between here and the close of the list below
		echo "<ul style='list-style-type: none;'>";
		
		// generic link to Admin.php
		echo "<li>";
		printAdminLink(gettext('Admin'), '', "</li>\n");
		// setup for return links
		if (isset($_GET['p'])) {
			$redirect = "&amp;p=" . $_GET['p'];
		}
		if ($page>1) {
			$redirect .= "&amp;page=$page";
		}
		
		if ($_zp_loggedin & (ADMIN_RIGHTS | OPTIONS_RIGHTS)) {
		// options link for all admins with options rights
			echo "<li>";
			printLink($zf . '/admin-options.php?tab=general', gettext("Options"), NULL, NULL, NULL);
			echo "</li>\n";
		}
		zp_apply_filter('admin_toolbox_global');
		
		$gal = getOption('custom_index_page');
		if (empty($gal) || !file_exists(SERVERPATH.'/'.THEMEFOLDER.'/'.getOption('current_theme').'/'.internalToFilesystem($gal).'.php')) {
			$gal = 'index.php';
		} else {
			$gal .= '.php';
		}
		if ($_zp_gallery_page === $gal) { 
		// script is either index.php or the gallery index page
			if ($_zp_loggedin & (ADMIN_RIGHTS | ALBUM_RIGHTS)) {
				// admin has edit rights so he can sort the gallery (at least those albums he is assigned)
				echo "<li>";
				printSortableGalleryLink(gettext('Sort gallery'), gettext('Manual sorting'));
				echo "</li>\n";
			}
			if ($_zp_loggedin & (ADMIN_RIGHTS | UPLOAD_RIGHTS)) {
				// admin has upload rights, provide an upload link for a new album
				?>
				<li>
					<a href="javascript:newAlbum('',true);" ><?php echo gettext("New Album"); ?></a>
				</li>
				<?php
			}
			zp_apply_filter('admin_toolbox_gallery');
		} else if ($_zp_gallery_page === 'album.php') { 
		// script is album.php
			$albumname = $_zp_current_album->name;
			if (isMyAlbum($albumname, ALBUM_RIGHTS)) {
				// admin is empowered to edit this album--show an edit link
				echo "<li>";
				printSubalbumAdmin(gettext('Edit album'), '', "</li>\n");
				echo "<li>";
				printLink(WEBPATH.'/' . ZENFOLDER . '/admin-edit-small.php?page=edit&tab=imageinfo&album=' . urlencode($_zp_current_album->name), 'Edit images', NULL, NULL, NULL);
				echo "<li>";
				printLink(WEBPATH.'/' . ZENFOLDER . '/admin-edit.php?page=edit&tab=imageinfo&album=' . urlencode($_zp_current_album->name), 'Move images', NULL, NULL, NULL);
				if (!$_zp_current_album->isDynamic()) {
					echo "<li>";
					printSortableAlbumLink(gettext('Sort album'), gettext('Manual sorting'));
					echo "</li>\n";
				}
				// and a delete link
				echo "<li><a href=\"javascript: confirmDeleteAlbum('".$zf."/admin-edit.php?page=edit&amp;action=deletealbum&amp;album=" .
					urlencode(urlencode($albumname)) .
					"','".js_encode(gettext("Are you sure you want to delete this entire album?"))."','".js_encode(gettext("Are you Absolutely Positively sure you want to delete the album? THIS CANNOT BE UNDONE!")).
					"');\" title=\"".gettext("Delete the album")."\">".gettext("Delete album")."</a></li>\n";
			}
			if (isMyAlbum($albumname, UPLOAD_RIGHTS) && !$_zp_current_album->isDynamic()) {
				// provide an album upload link if the admin has upload rights for this album and it is not a dynamic album
				?>
				<li>
					<?php echo printLink($zf . '/admin-upload.php?album=' . urlencode($albumname), gettext("Upload Here"), NULL, NULL, NULL); ?>
				</li>
				<li>
					<a href="javascript:newAlbum('<?php echo pathurlencode($albumname); ?>',true);" ><?php echo gettext("New Album Here"); ?></a>
				</li>
				<?php
			}
			// set the return to this album/page
			zp_apply_filter('admin_toolbox_album', $albumname);
			$redirect = "&amp;album=".urlencode($albumname)."&amp;page=$page";
			
		} else if ($_zp_gallery_page === 'image.php') {
			// script is image.php
			if (!$_zp_current_album->isDynamic()) { // don't provide links when it is a dynamic album
				$albumname = $_zp_current_album->name;
				$imagename = $_zp_current_image->filename;
				if (isMyAlbum($albumname, ALBUM_RIGHTS)) {
					// if admin has edit rights on this album, provide a delete link for the image.
					echo "<li><a href=\"javascript: confirmDeleteImage('".$zf."/admin-edit.php?page=edit&amp;action=deleteimage&amp;album=" .
					urlencode(urlencode($albumname)) . "&amp;image=". urlencode($imagename) . "','". js_encode(gettext("Are you sure you want to delete the image? THIS CANNOT BE UNDONE!")) . "');\" title=\"".gettext("Delete the image")."\">".gettext("Delete image")."</a>";
					echo "</li>\n";

					echo '<li><a href="'.$zf.'/admin-edit.php?page=edit&amp;album='.urlencode($albumname).'&amp;image='.urlencode($imagename).'&amp;tab=imageinfo#IT" title="'.gettext('Edit this image').'">'.gettext('Edit image').'</a></li>'."\n";
				}
				// set return to this image page
				zp_apply_filter('admin_toolbox_image', $albumname, $imagename);
				$redirect = "&amp;album=".urlencode($albumname)."&amp;image=".urlencode($imagename);
			}
		} else if (($_zp_gallery_page === 'search.php') && !empty($_zp_current_search->words)) {
			// script is search.php with a search string
			if ($_zp_loggedin & (ADMIN_RIGHTS | UPLOAD_RIGHTS)) {
				// if admin has edit rights allow him to create a dynamic album from the search
				echo "<li><a href=\"".$zf."/admin-dynamic-album.php\" title=\"".gettext("Create an album from the search")."\">".gettext("Create Album")."</a></li>";
			}
			zp_apply_filter('admin_toolbox_search');
			$redirect = "&amp;p=search" . $_zp_current_search->getSearchParams() . "&amp;page=$page";
		}
		
		// zenpage script pages
		if(function_exists('is_NewsArticle')) {
				if (is_NewsArticle()) {
					// page is a NewsArticle--provide zenpage edit, delete, and Add links
					$titlelink = getNewsTitlelink();
					$redirect .= '&amp;title='.urlencode($titlelink);
				}
				if (is_Pages()) {
					// page is zenpage page--provide edit, delete, and add links
					$titlelink = getPageTitlelink();
					$redirect .= '&amp;title='.urlencode($titlelink);
				}
				if ($_zp_loggedin & (ADMIN_RIGHTS | ZENPAGE_RIGHTS)) {
				// admin has zenpage rights, provide link to the zenpage admin tab
				echo "<li><a href=\"".$zf.'/'.PLUGIN_FOLDER."/zenpage/admin-news-articles.php\">".gettext("News")."</a></li>";
				if (is_NewsArticle()) {
					// page is a NewsArticle--provide zenpage edit, delete, and Add links
					echo "<li><a href=\"".$zf.'/'.PLUGIN_FOLDER."/zenpage/admin-edit.php?newsarticle&amp;edit&amp;titlelink=".urlencode($titlelink)."\">".gettext("Edit Article")."</a></li>";
					?> 
					<li><a href="javascript: confirmDeleteImage('<?php echo $zf.'/'.PLUGIN_FOLDER; ?>/zenpage/admin-news-articles.php?del=<?php echo getNewsID(); ?>','<?php echo js_encode(gettext("Are you sure you want to delete this article? THIS CANNOT BE UNDONE!")); ?>')" title="<?php echo gettext("Delete article"); ?>"><?php echo gettext("Delete Article"); ?></a></li>
					<?php
					echo "<li><a href=\"".$zf.'/'.PLUGIN_FOLDER."/zenpage/admin-edit.php?newsarticle&amp;add\">".gettext("Add Article")."</a></li>";
					zp_apply_filter('admin_toolbox_news', $titlelink);
				}
				echo "<li><a href=\"".$zf.'/'.PLUGIN_FOLDER."/zenpage/admin-pages.php\">".gettext("Pages")."</a></li>";
				if (is_Pages()) {
					// page is zenpage page--provide edit, delete, and add links
					echo "<li><a href=\"".$zf.'/'.PLUGIN_FOLDER."/zenpage/admin-edit.php?page&amp;edit&amp;titlelink=".urlencode($titlelink)."\">".gettext("Edit Page")."</a></li>";
					?> 
					<li><a href="javascript: confirmDeleteImage('<?php echo $zf.'/'.PLUGIN_FOLDER; ?>/zenpage/page-admin.php?del=<?php echo getPageID(); ?>','<?php echo js_encode(gettext("Are you sure you want to delete this page? THIS CANNOT BE UNDONE!")); ?>')" title="<?php echo gettext("Delete page"); ?>"><?php echo gettext("Delete Page"); ?></a></li>
					<?php	
					echo "<li><a href=\"".FULLWEBPATH."/".ZENFOLDER.'/'.PLUGIN_FOLDER."/zenpage/admin-edit.php?page&amp;add\">".gettext("Add Page")."</a></li>";
					zp_apply_filter('admin_toolbox_page', $titlelink);
				}
			}
		}	
		
		// logout link
		if (getOption('server_protocol')=='https') $sec=1; else $sec=0;
		echo "<li><a href=\"".$zf."/admin.php?logout={$sec}{$redirect}\">".gettext("Logout")."</a></li>\n";
		
		// close the list
		echo "</ul>\n";
		echo "</div>\n";
	}
}

function formatRatingCounter($array, $splitLines=true)
{
	$votes = $array[0];
	$views = $array[1];
	$score = $array[2];
	
	if ($votes == 0 AND $views == 0)
	{
		return '';
	}
	else
	{
		if (zp_loggedin())
		{
			$extra = " for a score of $score";
		}
		
		return '<br>'.pluralNumberWord($votes, 'vote').' from '.pluralNumberWord($views, 'view').$extra;
	}
}

function formatHitCounter($array, $splitLines=true)
{
	$alltime = $array[0];
	$month = $array[1];
	$week = $array[2];
	$galleryType = $array[3];
	
	// return just a single row, for the overall gallery listing pages
	if ($galleryType == 'this-month') {
		$toreturn = $month;
		$extraText = " this month";
	} else if ($galleryType == 'this-week') {
		$toreturn = $week;
		$extraText = " this week";
	} else if ($galleryType == 'all-time') {
		$toreturn = $alltime;
	}
	
	if ($toreturn > 0) {
		return "<br>Viewed ".pluralNumberWord($toreturn, 'time').$extraText;
	}
	
	// otherwise build up a massive string
	if ($alltime > 0) {
		$toreturn = "<br>Viewed ".pluralNumberWord($alltime, 'time');
	} else {
		return "";
	}
	
	if ($week > 0) {
		$toreturn .= "<br>(".pluralNumberWord($week, 'time')." this week";
		
		if ($month > 0) {
			$toreturn .= ", ".pluralNumberWord($month, 'time')." this month)";
		} else {
			$toreturn .= ")";
		}
	} else if ($month > 0) {
		$toreturn .= "<br>(".pluralNumberWord($month, 'time')." this month)";
	}
	
	// formattting fix for album page, when not in EXIF box
	if (!$splitLines) {
		$toreturn = str_replace('<br>',' ',$toreturn);
	}
	
	return $toreturn;
}

function printHitCounter($obj)
{
	echo formatHitCounter(array($obj->get('hitcounter'), $obj->get('hitcounter_month'), $obj->get('hitcounter_week')));
}

function incrementAndReturnHitCounter($option='image', $viewonly=false, $id=NULL) {
	global $_zp_current_image, $_zp_current_album;
	
	switch($option) {
		case "image":
			$obj = $_zp_current_image;
			if (is_null($id)) {
				$id = getImageID();
			}
			$dbtable = prefix('images');
			$doUpdate = true;
			break;
		case "album":
			$obj = $_zp_current_album;
			if (is_null($id)) {
				$id = getAlbumID();
			}
			$dbtable = prefix('albums');
			$doUpdate = getCurrentPage() == 1; // only count initial page for a hit on an album
			break;
	}
	
	// get currrent counters and dates
	if ($obj != null) {	
		$hitCounterAllTime = $obj->get('hitcounter');
		$hitCounterMonth = $obj->get('hitcounter_month');
		$hitCounterWeek = $obj->get('hitcounter_week');
	} else {
		$doUpdate = false;
	}
	
	// check to see if changing from small to full size, or vice versa, then don't update counter
	if (substr_count($_SERVER['HTTP_REFERER'], str_replace('?p=full','', $_SERVER['REQUEST_URI'])) > 0) {
		$doUpdate = false;
	}
	
	// update counters if required
	if ( $doUpdate ) {
		
		$hitCounterMonthLastReset = $obj->get('hitcounter_month_reset');
		$hitCounterWeekLastReset = $obj->get('hitcounter_week_reset');
		
		$updatedHitCounter = updateHitCounter($hitCounterAllTime, $hitCounterMonth, $hitCounterWeek, $hitCounterMonthLastReset, $hitCounterWeekLastReset);
		
		// update all counters if a public user
		if ( !zp_loggedin() ) 
		{
			query("UPDATE $dbtable SET ".$updatedHitCounter['public']." WHERE `id` = $id");
		} 
		// only reset the monthly and weekly totals if and admin, and counter are past the date
		else if ($updatedHitCounter['admin'] != '') 
		{
			query("UPDATE $dbtable SET ".$updatedHitCounter['admin']." WHERE `id` = $id");
		}
		
		$hitCounterAllTime = $updatedHitCounter['hitCounterAllTime'];
		$hitCounterMonth = $updatedHitCounter['hitCounterMonth'];
		$hitCounterWeek = $updatedHitCounter['hitCounterWeek'];
	}
	return array($hitCounterAllTime, $hitCounterMonth, $hitCounterWeek);
}

function updateHitCounter($hitCounterAllTime, $hitCounterMonth, $hitCounterWeek, $hitCounterMonthLastReset, $hitCounterWeekLastReset)
{
	$time = time();
	$dateCurrent = date("Y-m-d");
	$dateLastWeek = date("Y-m-d", $time - (60*60*24*6));
	$dateLastMonth = date("Y-m-d", $time - (60*60*24*28));
	
	// alter the various counts
	if ( !zp_loggedin() )
	{
		$hitCounterWeek++;
		$hitCounterMonth++;
		//$hitCounterAllTime++;
		$restartCount = 1;
	}
	else
	{
		$restartCount = 0;
	}
	
	// check last reset dates, fix if not set
	if ($hitCounterMonthLastReset == '0000-00-00' OR $hitCounterWeekLastReset == '0000-00-00') 
	{
		$adminSqlToUpdate = $publicSqlToUpdate = 
			", hitcounter_week_reset = '$dateCurrent', hitcounter_month_reset = '$dateCurrent', hitcounter_week = $restartCount, hitcounter_month = $restartCount";
		$hitCounterWeek = $restartCount;
		$hitCounterMonth = $restartCount;
	}
	else
	{
		
		// update week hit counters and last reset times if required
		if ($hitCounterWeekLastReset < $dateLastWeek) 
		{
			$sql = ", hitcounter_week_reset = '$dateCurrent', hitcounter_week = $restartCount ";
			$publicSqlToUpdate .= $sql;
			$adminSqlToUpdate .= $sql;
			$hitCounterWeek = $restartCount;
		}
		else 
		{
			$publicSqlToUpdate .= ", hitcounter_week = $hitCounterWeek ";
		}
		
		// update month hit counters and last reset times if required
		if ($hitCounterMonthLastReset < $dateLastMonth) 
		{
			$sql = ", hitcounter_month_reset = '$dateCurrent', hitcounter_month = $restartCount ";
			$publicSqlToUpdate .= $sql;
			$adminSqlToUpdate .= $sql;
			$hitCounterMonth = $restartCount;
		} 
		else 
		{
			$publicSqlToUpdate .= ", hitcounter_month = $hitCounterMonth ";
		}
	}
		
	// remove leading comma if required
	if (substr($adminSqlToUpdate, 0, 1) == ',') 
	{
		$adminSqlToUpdate = substr($adminSqlToUpdate, 1, strlen($adminSqlToUpdate));
	}
	
	if (substr($publicSqlToUpdate, 0, 1) == ',') 
	{
		$publicSqlToUpdate = substr($publicSqlToUpdate, 1, strlen($publicSqlToUpdate));
	}
	
	//$toreturn['public'] = "`hitCounter`= $hitCounterAllTime $publicSqlToUpdate";
	$toreturn['public'] = $publicSqlToUpdate;
	$toreturn['admin'] = $adminSqlToUpdate;
	$toreturn['hitCounterWeek'] = $hitCounterWeek;
	$toreturn['hitCounterMonth'] = $hitCounterMonth;
	$toreturn['hitCounterAllTime'] = $hitCounterAllTime;
	
	return $toreturn;
}


/**
 * Returns a randomly selected image from the gallery. (May be NULL if none exists)
 * @param bool $daily set to true and the picture changes only once a day.
 *
 * @return object
 */
function getRandomImagesSet($toReturn = 5) {
	global $_zp_gallery;
	
	$SQLwhere = prefix('images') . ".show=1 AND (" . prefix('images') . ".hitCounter > " . HITCOUNTER_THRESHOLD . " AND " . prefix('images') . ".ratings_score > " . RATINGS_THRESHOLD . ")";
	
	$offset_result = mysql_query( " SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM " . prefix('images') . " WHERE " . $SQLwhere);
	$offset_row = mysql_fetch_object( $offset_result );
	$offset = $offset_row->offset;
	
	$sql = " SELECT " . prefix('images') . ".title, " . prefix('images') . ".filename, " . prefix('albums') . ".folder
		FROM " . prefix('images') . "
		INNER JOIN " . prefix('albums') . " ON " . prefix('images') . ".albumid = " . prefix('albums') . ".id 
		WHERE " . $SQLwhere . " 
		LIMIT $offset, $toReturn ";
	$randomImagesResult = query_full_array( $sql );
	$imageCount = count($randomImagesResult);
	
	if ($imageCount != $toReturn)
	{
		return getRandomImagesSet($toReturn);
	}
	
	return $randomImagesResult;
}

function getThumbnailURLFromRandomImagesSet($array)
{
	return '/'.$array['folder']."/image/thumb/".$array['filename'];
}


	
?>