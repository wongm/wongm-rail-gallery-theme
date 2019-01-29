<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Welcome';
$rssType = 'Gallery';
$rssTitle = 'Recent uploads';

if (getOption('wongm_frontpage_mode') == 'albumsonly') {
	include_once('header.php');
?>
<div class="headbar">
<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo; Welcome!
</span><span id="righthead"><?echo printSearchForm(); ?></span>
</div>
<?php
    drawIndexAlbums('nodynamic');
	include_once('footer.php');
} else {
    include_once('frontpage.php');
}
?>