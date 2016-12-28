<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

/*
 * draw all dynamic albums in the gallery
 *
 */
if(isset($_REQUEST['by-theme']))
{
	$pageTitle = ' - Albums by theme';
	$rssType = 'Gallery';
	$rssTitle = 'Recent uploads';

	include_once('header.php');
?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<a href="/gallery" title="Albums by theme">Albums by theme</a>
	</span><span id="righthead"><?printSearchForm();?></span>
</div>
<?
	drawIndexAlbums('dynamiconly');
}
/*
 * draw all albums in the gallery
 *
 */
else
{
	$pageTitle = ' - All albums';
	$rssType = 'Gallery';
	$rssTitle = 'Recent uploads';

	include_once('header.php');
	global $totalGalleryAlbumCount;
?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<a href="<?=EVERY_ALBUM_PATH?>" title="All albums">All albums</a>
	</span><span id="righthead"><?printSearchForm();?></span>
</div>
<div class="topbar">
  	<h2>All albums</h2>
	<span>All <?=$totalGalleryAlbumCount?> albums that are currently on the site.</span>
</div>
<?
	drawIndexAlbums('nodynamic');

	if(hasNextPage() || hasPrevPage())
	{
		printPageListWithNav("« ".gettext("Prev"), gettext("Next")." »");
	}
}
include_once('footer.php');
?>