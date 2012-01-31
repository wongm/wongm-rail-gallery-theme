<?php 
/*
 * Present the different types of ordered photo pages
 * - highest this month
 * - highest this week
 * - highest of all time
 * - highest ranking
 *
 */ 
$startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

setupStatisticsPage();

	
	if ($pageTypeModifier == 'ratings')
	{
		$trailingIntroText = '. '.RATINGS_TEXT;
	}
	
$pageTitle = " - " . getStatisticsPageTitle();	
include_once('header.php'); 
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<?=getStatisticsPageBreadCrumbs('&raquo;')?>
	</td><td id="righthead"><?printSearchBreadcrumb();?></td></tr>
</table>
<?

global $_zp_current_photostream_current_image;

drawRecentImagesNextables();

echo "<table class=\"centeredTable\">\n";
$i=0;
$j=0;

if ($numberOfRows == '4')
{
	$j=1;
}
else
{
	$style = 'width="30%" ';
}
loadNextPhotostreamImage();

while ($_zp_current_photostream_current_image != null)
{
	if ($j == 0)
	{
		echo "<tr>\n";
	}
?>
<td class="i" <?=$style ?>><a href="<?=$_zp_current_photostream_current_image['imagePageLink'] ?>">
	<img src="<?=$_zp_current_photostream_current_image['imageUrl'] ?>" alt="<?=$_zp_current_photostream_current_image['photoDesc'] ?>" title="<?=$_zp_current_photostream_current_image['photoDesc'] ?>" /></a>
	<h4><a href="<?=$_zp_current_photostream_current_image['imagePageLink'] ?>"><?=$_zp_current_photostream_current_image['photoTitle'] ?></a></h4>
	<small><?=$_zp_current_photostream_current_image['photoDate'] ?><?=$_zp_current_photostream_current_image['photoStatsText'] ?></small><br/>
	In Album: <a href="<?=$_zp_current_photostream_current_image['albumPageLink'] ?>"><?=$_zp_current_photostream_current_image['photoAlbumTitle'] ?></a>
</td>
<?
	$j++;
	$i++;
	
	if ($j == 3)
	{
		$j=0;
		echo "<tr>\n";
	}
	
	loadNextPhotostreamImage();
}
	
echo "</table>\n";
	
drawRecentImagesNextables(true);

include_once('footer.php'); 
?>