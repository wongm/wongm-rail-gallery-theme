<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - All albums';
$rssType = 'Gallery';
$rssTitle = 'Recent uploads';

include_once('header.php');
global $totalGalleryAlbumCount;
?>
<div class="headbar">
<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo;
<a href="<?php echo EVERY_ALBUM_PATH; ?>" title="All albums">All albums</a>
</span><span id="righthead"><?echo printSearchForm(); ?></span>
</div>
<div class="topbar">
	<h2>All albums</h2>
<span>All <?php echo $totalGalleryAlbumCount; ?> albums that are currently on the site.</span>
</div>
<?php
drawIndexAlbums('nodynamic');

if(hasNextPage() || hasPrevPage())
{
	printPageListWithNav("« ".gettext("Prev"), gettext("Next")." »");
}
include_once('footer.php');
?>