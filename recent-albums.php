<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$rssType = 'AlbumsRSS';
$rssTitle = 'Recent albums';
$pageTitle = ' - '.$rssTitle;

include_once('header.php');
global $totalGalleryAlbumCount;
?>
<div class="headbar">
	<span id="breadcrumb"><span class="lede"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo;</span>
	<a href="<?php echo RECENT_ALBUM_PATH; ?>" title="Recent albums">Recent albums</a>
	</span><span id="righthead"><?php printSearchForm(); ?></span>
</div>
<div class="topbar">
  	<h2><?php echo $rssTitle; ?></h2>
	<span>Occasionally I will add new albums that contain older photos, here are the most recently added. There are <?php echo $totalGalleryAlbumCount; ?> in total.</span>
</div>
<?php
drawIndexAlbums('recent');
if(hasNextPage() || hasPrevPage())
{
	printPageListWithNav("« ".gettext("Prev"), gettext("Next")." »");
}
include_once('footer.php');
?>