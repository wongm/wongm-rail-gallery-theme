<?php
if(isset($_REQUEST['recent-albums']))
{
	$rssType = 'AlbumsRSS';
	$rssTitle = 'Recent albums';
	$pageTitle = ' - '.$rssTitle;
	
	include_once('header.php'); 
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<a href="<?=RECENT_ALBUM_PATH?>" title="Recent albums">Recent albums</a>
	</td><td><?printSearchForm();?></td></tr>
</table>
<p>Occasionally I will add new albums that contain older photos, here are the <?=MAXALBUMS_PERPAGE?> most recently added</p>
<?
	drawIndexAlbums('recent');	
}
/*
 * draw all albums in the gallery
 * 
 */
else if(isset($_REQUEST['all-albums']))
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
<?
	$prevPageUrl = EVERY_ALBUM_PATH.getPrevPageURL();
	$nextPageUrl = EVERY_ALBUM_PATH.getNextPageURL();
	
	if(hasNextPage() || hasPrevPage())
	{	?>
  <table class="nextables"><tr><td>
    <?php if (hasPrevPage()) { ?> <a class="prev" href="<?=$prevPageUrl;?>" title="Previous Page"><span>&laquo;</span> Previous</a> <?php } ?>
    <?php if (hasNextPage()) { ?> <a class="next" href="<?=$nextPageUrl;?>" title="Next Page">Next <span>&raquo;</span></a><?php } ?>
  </td></tr></table>
  <? }
  
	drawIndexAlbums('nodynamic');
	
	if(hasNextPage() || hasPrevPage())
	{
?>
<table class="nextables"><tr><td>
    <?php if (hasPrevPage()) { ?> <a class="prev" href="<?=$prevPageUrl;?>" title="Previous Page"><span>&laquo;</span> Previous</a> <?php } ?>
    <?php if (hasNextPage()) { ?> <a class="next" href="<?=$nextPageUrl;?>" title="Next Page">Next <span>&raquo;</span></a><?php } ?>
</td></tr></table>
<div class="pages">
<?php drawGalleryPageNumberLinks(EVERY_ALBUM_PATH); ?>
</div>
<? 
	}
}
/*
 * draw all dynamic albums in the gallery
 * 
 */
else if(isset($_REQUEST['by-theme']))
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
include_once('footer.php');
?>