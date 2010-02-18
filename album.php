<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

$pageTitle = ' - '.getAlbumTitle();
$rssType = 'Album';
$rssTitle = getAlbumTitle();

include_once('header.php'); 
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; 
      	<?php printParentBreadcrumb('', ' &raquo; ', ' &raquo; '); ?>
      	<?php printAlbumTitle(true);?>
	</td><td><?printSearchForm();?></td></tr>
</table>

<div class="topbar">
  	<h3>Album: <?=getAlbumTitle();?></h3>
  	<?php printAlbumDesc(true); ?>
</div>
<?
	drawWongmAlbumNextables(false, getAlbumLinkURL().'page/');
 	drawWongmListSubalbums();
	
 	/* Only print if we have images. */
 	$num = getNumImages(); 
  	if ($num > 0)
  	{
  		drawWongmGridImages();
	}	
	
	drawWongmAlbumNextables(true, getAlbumLinkURL().'page/');
	echo "<h4>Tags</h4>";
	printTags('links', '', '', '', false);
	
	echo "<p>".formatHitCounter(incrementAndReturnHitCounter('album'), false)."</p>";
	include_once('footer.php');
?>