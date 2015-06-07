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
$photoDesc = "";

while ($i < getOption('wongm_randompage_count'))
{
	echo '<tr class="trio">';

	while ($j < 3)
	{
		$randomImage = getRandomImages();
		$randomImageURL = $randomImage->getLink();
		$photoTitle = $randomImage->getTitle();
		$photoDate = strftime(getOption('date_format'), strtotime($randomImage->getDateTime()));
		$imageCode = "<img src='".$randomImage->getThumb()."' alt='".$photoTitle."'>";

		$albumForPhoto = $randomImage->getAlbum();
		$photoAlbumTitle = $albumForPhoto->getTitle();
		$photoPath = $albumForPhoto->getLink();

		if ($photoDesc == '')
		{
			$photoDesc = $photoTitle;
		}
		else
		{
			$photoDesc = 'Description: '.$photoDesc;
		}
?>
<td class="image">
	<div class="imagethumb"><a href="http://<?=$_SERVER['HTTP_HOST'].$randomImageURL?>">
		<?=$imageCode?>
	</a></div>
	<div class="imagetitle">	
		<h4><a href="http://<?=$_SERVER['HTTP_HOST'].$randomImageURL?>"><?=$photoTitle; ?></a></h4>
		<small><?php echo $photoDate ?><?php if (zp_loggedin())
		{
			printRollingHitcounter($randomImage, true);
		} 
		?></small>
		<p>In Album: <a href="http://<?=$_SERVER['HTTP_HOST'].$photoPath; ?>"><?=$photoAlbumTitle; ?></a></p>
	</div>
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