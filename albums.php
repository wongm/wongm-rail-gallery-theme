<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Albums by theme';
$rssType = 'Gallery';
$rssTitle = 'Recent uploads';

include_once('header.php');
?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo;
	<a href="<?php echo ALBUM_THEME_PATH; ?>" title="Albums by theme">Albums by theme</a>
	</span><span id="righthead"><?echo printSearchForm(); ?></span>
</div>
<?php
drawIndexAlbums('dynamiconly');
include_once('footer.php');
?>