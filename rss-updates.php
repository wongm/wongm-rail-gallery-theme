<?php
$_zp_script_timer['start'] = microtime();
// force UTF-8 Ø
startRSSCache();

header('Content-Type: application/xml');
$locale = getRSSLocale();
$validlocale = getRSSLocaleXML();
$host = getRSSHost();
$protocol = SERVER_PROTOCOL;
$albumname = getRSSAlbumTitle();
$size = getOption('image_size');
$items = getOption('RSS_items'); // # of Items displayed on the feed

$alldates = array();
$cleandates = array();
$sql = "SELECT `date` FROM ". prefix('images');
if (!zp_loggedin()) {
	$sql .= " WHERE `show` = 1";
}
$hidealbums = getNotViewableAlbums();
if (!is_null($hidealbums)) {
	if (zp_loggedin()) {
		$sql .= ' WHERE ';
	} else {
		$sql .= ' AND ';
	}
	foreach ($hidealbums as $id) {
		$sql .= '`albumid`!='.$id.' AND ';
	}
	$sql = substr($sql, 0, -5);
}

$result = query_full_array($sql);
foreach($result as $row){
	$alldates[] = $row['date'];
}
foreach ($alldates as $adate) {
	if (!empty($adate)) {
		$cleandates[] = substr($adate, 0, 10);
	}
}
$datecount = array_count_values($cleandates);
krsort($datecount);
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<title><?php echo strip_tags(get_language_string(getOption('gallery_title'), $locale)).' '.strip_tags($albumname); ?></title>
<link><?php echo $protocol."://".$host.WEBPATH; ?></link>
<atom:link href="<?php echo $protocol; ?>://<?php echo html_encode($_SERVER["HTTP_HOST"]); ?><?php echo html_encode($_SERVER["REQUEST_URI"]); ?>" rel="self"	type="application/rss+xml" />
<description><?php echo strip_tags(get_language_string(getOption('Gallery_description'), $locale)); ?></description>
<language><?php echo $validlocale; ?></language>
<pubDate><?php echo date("r", time()); ?></pubDate>
<lastBuildDate><?php echo date("r", time()); ?></lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>ZenPhoto RSS Generator</generator>
	<?php
	
	$i = 0;

	foreach ($datecount as $dateValue=>$numberOfImages )
	{
		if ($i == $items)
		{
			break;
		}
		
		$i++;
		$d1 = $dateValue." 00:00:00";
		$d2 = $dateValue." 23:59:59";
		
		$imageSql = "SELECT i.hitcounter, filename, folder, a.title AS title, i.date AS date FROM ". prefix('images') ." AS i
			INNER JOIN ". prefix('albums') ." AS a ON i.albumid = a.id
			WHERE i.`date` >= \"$d1\" AND i.`date` < \"$d2\" ORDER BY i.hitcounter DESC LIMIT 0, 1";
		$thumbnailImageResult = query_full_array($imageSql);
		
		$imageSql = "SELECT filename, folder, a.title AS title, i.date AS date FROM ". prefix('images') ." AS i
			INNER JOIN ". prefix('albums') ." AS a ON i.albumid = a.id
			WHERE i.`date` >= \"$d1\" AND i.`date` < \"$d2\" GROUP BY i.albumid";
		$albumListResult = query_full_array($imageSql);
				
		$heading = date("l, F j Y",strtotime($dateValue));
		
		$thumbUrl = str_replace( ".jpg", "_" . $size . ".jpg", $thumbnailImageResult[0]['filename']);
		$thumbUrl = str_replace( ".JPG", "_" . $size . ".jpg", $thumbUrl);
		$imageURL = "railgallery.wongm.com/cache/" . $thumbnailImageResult[0]['folder'] . "/$thumbUrl";
		$itemlink = "railgallery.wongm.com/gallery/search/archive/$dateValue";
		
		$thumburl = '<img border="0" src="'. $protocol.'://'. $imageURL.'" alt="'. $heading .'" />';
		$thumburl = '<a title="'.$heading.'" href="'.$protocol.'://'.$itemlink.'">'.$thumburl.'</a>'.
		
		$albumList = $albumPlural = $imagePlural = "";
		$albumCount = 1;
		
		if ($numberOfImages > 1)
		{
			$imagePlural = "s";
		}
		
		foreach($albumListResult as $album)
		{
			if ($albumCount == count($albumListResult) AND $albumCount > 1)
			{
				$albumList .= " and ";
				$albumPlural = "s";
			}
			else if ($albumCount > 1)
			{
				$albumList .= ", ";
			}
			
			$text = $album['title'];
			if ($locale !== 'all') {
                $text = get_language_string($text, $locale);
            }
            $text = zpFunctions::unTagURLs($text);

			$albumList .= $text;
			$albumCount++;
		}
		
		$itemcontent = "<![CDATA[$thumburl<p>New photo$imagePlural in the $albumList album$albumPlural.</p>]]>";
		$heading = "$heading - $numberOfImages new image$imagePlural";
		?>
<item>
<title><?php 
	echo $heading;
?></title>
<link>
<?php echo '<![CDATA['.$protocol.'://'.$itemlink. ']]>';?>
</link>
<description>
<?php echo $itemcontent; ?>
</description>
<?php // enables download of embeded content like images or movies in some rss clients. just for testing, shall become a real option
if(getOption("RSS_enclosure")) { ?>
<enclosure url="<?php echo $protocol; ?>://<?php echo $fullimagelink; ?>" type="<?php echo $mimetype; ?>" length="<?php echo filesize($imagefile);?>" />
<?php  } ?>
<guid><?php echo '<![CDATA['.$protocol.'://'.$itemlink.']]>';?></guid>
<pubDate>
	<?php
	echo date("r",strtotime($albumListResult[0]['date']));
	?>
</pubDate>
</item>
<?php } ?>
</channel>
</rss>
<?php endRSSCache(); 
?>