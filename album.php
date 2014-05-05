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
  	<h2><?php printAlbumTitle(true);?></h2>
  	<?php printAlbumDesc(true); ?>
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

	echo "<p>".formatHitCounter(incrementAndReturnHitCounter('album'), false)."</p>";
	include_once('footer.php');
?>