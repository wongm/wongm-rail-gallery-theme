<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$title = "Stats";
$pageTitle = " - $title";
include_once('header.php');

?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo; 
	<?=$title?>
	</span><span id="righthead"><?php echo printSearchForm(); ?></span>
</div>
<?php
if (zp_loggedin()) 
{
	$averageHitsAcrossGallery = query_full_array("SELECT AVG( zen_images.hitcounter ) AS 'AVG'
		FROM zen_images
		ORDER BY AVG( zen_images.hitcounter ) 
		LIMIT 0 , 1000")[0]['AVG'];
	
	$averageHitsAcrossAlbums = query_full_array("SELECT zen_albums.title AS title, AVG( zen_images.hitcounter ) AS average, folder, zen_albums.hitcounter AS hitcounter
		FROM zen_images
		INNER JOIN zen_albums ON zen_images.albumid = zen_albums.id
		GROUP BY `albumid` 
		ORDER BY AVG( zen_images.hitcounter ) DESC 
		LIMIT 0 , 1000");
		
	$averageAlbumHits = query_full_array("SELECT folder, zen_albums.title AS title, zen_albums.hitcounter AS hitcounter
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
<tr style="text-align: left"><th width="400px">Album</th><th width="100px">Average views</th><th width="100px">Album views</th><th></th><th width="400px">Album</th><th width="100px">Album views</th></tr>
<?php
	$albumIndex = 0;
	foreach($averageHitsAcrossAlbums as $imagerow)
	{
?>
	<tr><td>(<a href="/<?=$imagerow['folder'] ?>">=</a>) <?=get_language_string($imagerow['title'])?></td>
		<td><?=number_format($imagerow['average'], 0)?></td>
		<td><?=number_format($imagerow['hitcounter'], 0)?></td>
		<td width="100"></td>
		<td>(<a href="/<?=$averageAlbumHits[$albumIndex]['folder'] ?>">=</a>) <?=get_language_string($averageAlbumHits[$albumIndex]['title'])?></td>
		<td><?=number_format($averageAlbumHits[$albumIndex]['hitcounter'], 0)?></td></tr>
<?php
		$albumIndex++;
	}
?>
</table>
<style>
td:nth-child(2), td:nth-child(3), td:nth-child(6), th:nth-child(2), th:nth-child(3), th:nth-child(6) { text-align: end; }
</style>
<?php
}
include_once('footer.php'); ?>