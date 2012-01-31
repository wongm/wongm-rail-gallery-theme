<?php
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
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<a href="/gallery" title="Albums by theme">Albums by theme</a></td>
	<td><?printSearchForm();?></td></tr>
</table>
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
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<a href="<?=EVERY_ALBUM_PATH?>" title="All albums">All albums</a>
	</td><td><?printSearchForm();?></td></tr>
</table>
<div class="topbar">
  	<h2>All albums</h2>
	<span>All <?=$albumNumber?> albums that are currently on the site.</span>
</div>
<?
	$prevPageUrl = EVERY_ALBUM_PATH . '/page/' . (getCurrentPage() - 1) . '/';
	$nextPageUrl = EVERY_ALBUM_PATH . '/page/' . (getCurrentPage() + 1) . '/';

	drawIndexAlbums('nodynamic');

	if(hasNextPage() || hasPrevPage())
	{
		printPageListWithNav("&laquo; ".gettext("Prev"), gettext("Next")." &raquo;");
	}
}
include_once('footer.php');
?>