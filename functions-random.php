<?php

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



/**
 * Returns a randomly selected image from the gallery. (May be NULL if none exists)
 * @param bool $daily set to true and the picture changes only once a day.
 *
 * @return object
 */
function getRandomImagesSet($toReturn = 5) {
	global $_zp_gallery;
	global $_randomImageAttempts;
	
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
	
	if ($imageCount != $toReturn AND $_randomImageAttempts < 5)
	{
		$_randomImageAttempts++;
		return getRandomImagesSet($toReturn);
	}
	
	return $randomImagesResult;
}

function getThumbnailURLFromRandomImagesSet($array)
{
	return '/'.$array['folder']."/image/thumb/".$array['filename'];
}


?>