<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

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
<div class="topbar">
  	<h2><?=$rssTitle?></h2>
	<span>Occasionally I will add new albums that contain older photos, here are the most recently added.</span>
</div>
<?
drawIndexAlbums('recent');
if(hasNextPage() || hasPrevPage())
{
	printPageListWithNav("« ".gettext("Prev"), gettext("Next")." »");
}
include_once('footer.php');
?>