<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

/*
 * Shows a list of different types of high rating photo page
 * - highest this month
 * - highest this week
 * - highest of all time
 * - highest ranking
 *
 */ 

$pageTitle = ' - Popular photos';
include_once('header.php');
$pageBreadCrumb = 'Popular photos';
?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<?=$pageBreadCrumb?>
	</span><span id="righthead"><?printSearchForm();?></span>
</div>
<?

foreach (array('this-week', 'ratings', 'this-month', 'all-time') AS $viewType)
{
	if ($viewType == 'ratings')
	{
		$extraText = ' ('.RATINGS_TEXT.')';
	}
	else
	{
		$extraText = '';
	}
	
	echo '<div class="topbar"><h2>'.$popularImageText[$viewType]['text']."</h2>\n";
	echo "<p><a href=\"".$popularImageText[$viewType]['url']."\">View more...</a>$extraText</p></div>";

	setCustomPhotostream($popularImageText[$viewType]['where'], "", $popularImageText[$viewType]['order']);
?>
<div id="imagewrapper">
	<div id="images">
<?php
	$count = 0;

	// draw top 3 items
	while (next_photostream_image() && (++$count <= 3))
	{
		drawWongmImageCell($viewType);
	}
?>
	</div>
</div>
<?
}
?>
<style>
/* big screens */
@media screen and (min-width: 600px) {
	#imagewrapper .image {
		width: calc(33% - 14px);
	}
}
</style>
<?php
include_once('footer.php'); 
?>