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
  	<h2>Album: <?=getAlbumTitle();?></h2>
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
?>
<table class="nextables"><tr id="pagelinked"><td>
	<?php if (hasPrevPage()) { ?> <a class="prev" href="<?=getMyPageURL(getPrevPageURL());?>" title="Previous Page"><span>&laquo;</span> Previous</a> <?php } ?>
	</td><td><?php printPageListWithNav(null, null, false, false, 'pagelist', null, true, 9); ?></td><td>
	<?php if (hasNextPage()) { ?> <a class="next" href="<?=getMyPageURL(getNextPageURL());?>" title="Next Page">Next <span>&raquo;</span></a><?php } ?>
</td></tr></table>
<?php
	}

	printTags('links', 'Tags');

	echo "<p>".formatHitCounter(incrementAndReturnHitCounter('album'), false)."</p>";
	include_once('footer.php');
?>