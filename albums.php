<?php

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
include_once('footer.php');
?>