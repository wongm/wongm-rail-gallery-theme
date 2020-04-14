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
<div id="imagewrapper">
	<div id="images">
<?php
while (next_album(true))
{
?>
<div class="image">
	<div class="imagethumb"><a href="<?php echo getAlbumURL();?>" title="<?php echo getAlbumTitle();?>">
		<?php printSizedAlbumThumbImage(getAlbumTitle()); ?>
	</a></div>
	<div class="imagetitle">
		<h4><a href="<?php echo getAlbumURL();?>" title="<?php echo getAlbumTitle();?>"><?php echo getAlbumTitle(); ?></a></h4>
		<?php echo printAlbumDesc(); ?>
	</div>
</div>
<?php
}
?>
	</div>
</div>
<?php
	include_once('footer.php');
} else {
    include_once('frontpage.php');
}
?>