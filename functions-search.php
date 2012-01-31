<?php

/******************************************************************************
// Search and show updates functions for the ZenPhoto gallery that I need
//
// Old school (non integrated) search and for use in 404 pages
//
// For railgeelong.com and wongm.railgeelong.com
//
// V 1.0.0

	Requires:
	GALLERY_PATH
	UPDATES_URL_PATH
	MAXIMAGES_PERPAGE

//
//*****************************************************************************/

/**
 * Runs a search for a given term, for either image or album. supports paganation
 *
 * @param in $page - the number of the page to be viewed. Integer greater than 1
 */
function imageOrAlbumSearch($term, $type)
{
	// setup search options
	//404 page version
	$maxImagesPerPage = 3;
	$index = 0;
	
	// do the query
	if ($type == 'image')
	{
		setCustomPhotostream("i.title like '%$term%' OR i.`desc` like '%$term%' OR i.filename like '%$term%'", "", "");
		$text2 = ' <a href="'.GALLERY_PATH.'/search/?search='.$term.'&type=albums">Search for \''.$term.'\' in albums instead?</a>';
		$numberOfRows = getNumPhotostreamImages();
	}
	elseif ($type == 'album')
	{
		$niceTerm = str_replace('-', ' ', $term);
		$folderTerm = $term;

		$searchSql = "SELECT * FROM " . prefix('albums') . " a WHERE ( a.title LIKE '%$niceTerm%' OR a.`desc` LIKE '%$niceTerm%' OR a.folder LIKE '%$folderTerm%' )";
		$text2 = ' <a href="'.GALLERY_PATH.'/search/?search='.$term.'&type=image">Search for \''.$term.'\' in images instead?</a>';
		$galleryResult = query_full_array($searchSql);
		$numberOfRows = sizeof($galleryResult);
	}
	
	// display infomation to the user
	// if this is being called from the 404 error page, display stripped down formatting
	if($numberOfRows == 0)
	{
		// for no results found and on error page
		return;
	}
		
	// display results
	// if this is being called from the 404 error page, display stripped down formatting
	if ($type == 'image')
	{
?>
<table class="centeredTable">
	<tr>
<?php
		// draw top 3 items
		while (next_photostream_image() && (++$count <= 3))
		{
			drawWongmImageCell($pageType);
		}
?>
	</tr>
</table>
<?
	}
	elseif ($type == 'album')
	{
		drawAlbums($galleryResult, true, true);
	}
	else
	{
		echo ("<p>No results found for '$term'");
	}
	return $numberOfRows;
}




/* 
 * pass it an SQL result set
 * used by album search
 * and by the frontpage recently updated search
 */
function drawAlbums($galleryResult, $error = false, $search = false)
{
	$numberOfRows = sizeof($galleryResult);
		
	if ($numberOfRows>0) 
	{	
		echo '<table class="centeredTable">';
		$i=0;
		$j=0;
			
		while ($i<$numberOfRows AND $i<3)
		{
			echo "<tr>\n";
			while ($j < 3 AND $i<$numberOfRows)
			{
				$photoPath = $galleryResult[$i]['folder'];
				$photoAlbumTitle = stripslashes($galleryResult[$i]["title"]);
				$albumId = $galleryResult[$i]["id"];
								
				$albumDate = strftime(TIME_FORMAT, strtotime($galleryResult[$i]["date"]));
				
				// get an image to display with it
				$imageSql = "SELECT filename, id FROM " . prefix('images') . " i WHERE i.albumid = '$albumId' ORDER BY hitcounter DESC LIMIT 0,1 ";
				$imageResult = MYSQL_QUERY($imageSql);
				$numberOfImages = MYSQL_NUM_ROWS($imageResult);
				if ($numberOfImages > 0)
				{
					$photoUrl = MYSQL_RESULT($imageResult,0,"filename");
					$photoId = MYSQL_RESULT($imageResult,0,"id");
					$thumbUrl = replace_filename_with_cache_thumbnail_version($photoUrl);
					$photoUrl = GALLERY_PATH."/cache/$photoPath/$thumbUrl";
				}
				else
				{
					$photoUrl = GALLERY_PATH."/foldericon.gif";
				}
				
				if ($photoDesc == '')
				{
					$photoDesc = $photoTitle;
				}
				else
				{
					$photoDesc = 'Description: '.$photoDesc;
				}
?>
<td class="i"><a href="<?=GALLERY_PATH ?>/<? echo $photoPath; ?>/"><img src="<?=$photoUrl ?>" alt="<? echo $photoAlbumTitle; ?>" title="<? echo $photoAlbumTitle; ?>" /></a>
	<br/><div class="imagetitle"><h4><a href="<?=GALLERY_PATH ?>/<? echo $photoPath; ?>/"><? echo $photoAlbumTitle; ?></a></h4>
	<small><?=$albumDate?></small></div></td>
<?
				$j++;
				$i++;
			
			}	//end while for cols
			$j=0;
			echo "</tr>\n";
		}	//end while for rows
		echo "</table>\n";
	}	// end if for non zero
}		// end function

?>