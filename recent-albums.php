<?php
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
include_once('footer.php');
?>