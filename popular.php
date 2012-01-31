<?php 
/*
 * Shows a list of different types of high rating photo page
 * - highest this month
 * - highest this week
 * - highest of all time
 * - highest ranking
 *
 */ 
$startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Popular photos';
include_once('header.php');
require_once("functions-search.php");
$pageBreadCrumb = 'Popular photos';
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<?=$pageBreadCrumb?>
	</td><td id="righthead"><? printSearchForm();?></td></tr>
</table>
<?

foreach (array('this-week', 'ratings', 'this-month', 'all-time') AS $viewType)
//foreach (array('this-week', 'this-month', 'all-time') AS $viewType)
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
<table class="centeredTable">
	<tr class="trio">
<?php
	$count = 0;

	// draw top 3 items
	while (next_photostream_image() && (++$count <= 3))
	{
		drawWongmImageCell($viewType);
	}
	
	while (++$count <= 3)
	{
		echo "<td class=\"image\"></td>";
	}
?>
	</tr>
</table>
<?

}

include_once('footer.php'); 
?>