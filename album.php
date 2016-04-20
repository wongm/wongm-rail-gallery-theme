<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

$pageTitle = ' - '.getAlbumTitle();
$rssType = 'Album';
$rssTitle = getAlbumTitle();

include_once('header.php');
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
      	<?php printParentBreadcrumb('', ' » ', ' » '); ?>
      	<?php echo getAlbumTitle();?>
	</td><td><?php printSearchForm();?></td></tr>
</table>

<div class="topbar">
  	<h2><?php printEditableAlbumTitle(true);?></h2>
  	<?php printEditableAlbumDesc(true); ?>
</div>
<?
 	drawWongmListSubalbums();

 	/* Only print if we have images. */
 	$num = getNumImages();
  	if ($num > 0)
  	{
  		drawWongmGridImages($num);
	}

	if (hasPrevPage() || hasNextPage())
  	{
		printPageListWithNav("« " . gettext("Prev"), gettext("Next") . " »");
	}

	printTags('links', 'Tags');
	
if (function_exists('printGoogleMap') && zp_loggedin()) {
    	printGoogleMap();
    }

	global $_zp_current_album;
	echo "<p>".getRollingHitcounter($_zp_current_album, '', false)."</p>";
	include_once('footer.php');
?>