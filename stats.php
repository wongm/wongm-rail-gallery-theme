<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$title = "Stats";
$pageTitle = " - $title";
include_once('header.php');

?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; <?=$title?>
	</td><td><?printSearchForm();?></td></tr>
  </table>
<?
if (zp_loggedin()) 
{
	$averageHitsAcrossGallery = MYSQL_RESULT(MYSQL_QUERY("SELECT AVG( zen_images.hitcounter ) AS 'AVG'
		FROM zen_images
		ORDER BY AVG( zen_images.hitcounter ) 
		LIMIT 0 , 1000"), 0, 'AVG');
	
	$averageHitsAcrossAlbums = MYSQL_QUERY("SELECT zen_albums.title AS title, AVG( zen_images.hitcounter ) AS average, folder, zen_albums.hitcounter AS hitcounter
		FROM zen_images
		INNER JOIN zen_albums ON zen_images.albumid = zen_albums.id
		GROUP BY `albumid` 
		ORDER BY AVG( zen_images.hitcounter ) DESC 
		LIMIT 0 , 1000");
		
	$averageAlbumHits = MYSQL_QUERY("SELECT folder, zen_albums.title AS title, zen_albums.hitcounter AS hitcounter
		FROM zen_albums
		ORDER BY hitcounter DESC 
		LIMIT 0 , 1000");
	
?>
<div class="topbar">
	<h2><?=$title?></h2>
</div>
<p>Average of <?=$averageHitsAcrossGallery?> views per image across the gallery.</p>

<p>By album:</p>
<table style="margin-left:4em;" cellpadding="3">
<tr style="text-align: left"><th width="400px">Album</th><th width="200px">Average views</th><th width="100px">Album views</th><th></th><th width="400px">Album</th><th width="100px">Album views</th></tr>
<?
	
	while($imagerow = mysql_fetch_assoc($averageHitsAcrossAlbums)) 
	{
		$albumrow = mysql_fetch_assoc($averageAlbumHits)
?>
	<tr><td>(<a href="/<?=$imagerow['folder'] ?>">=</a>) <?=$imagerow['title']?></td>
		<td><?=$imagerow['average']?></td>
		<td><?=$imagerow['hitcounter']?></td>
		<td width="100"></td>
		<td>(<a href="/<?=$albumrow['folder'] ?>">=</a>) <?=$albumrow['title']?></td>
		<td><?=$albumrow['hitcounter']?></td></tr>
<?
	}
?>
</table>
<?	
}
include_once('footer.php'); ?>