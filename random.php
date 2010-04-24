<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

$pageTitle = ' - Random photos';
include_once('header.php');
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<a href="<?=RANDOM_ALBUM_PATH?>" title="Random Images">Random photos</a>
	</td><td><?printSearchForm();?></td></tr>
</table>
<div class="topbar">
	<h2>Random images</h2>
	<p>A selection of random photos each time you refresh the page</p>
</div>
<?php

echo "<table class=\"centeredTable\">";
$i=0;
$j=0;

while ($i < getOption('wongm_randompage_count'))
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
	<small><?=$photoDate?><br/><? printHitCounter($randomImage); ?></small><br/>
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
include_once('footer.php');
?>