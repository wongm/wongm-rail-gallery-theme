<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

$pageTitle = ' - '.getAlbumTitle();
$rssType = 'Album';
$rssTitle = getAlbumTitle();

include_once('header.php');
?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo;
      	<?php printParentBreadcrumb('', ' » ', ' » '); ?>
      	<?php echo getAlbumTitle();?>
	</span><span id="righthead"><?php echo printSearchForm(); ?></span>
</div>

<div class="topbar">
  	<h2><?php printMWEditableAlbumTitle(true);?></h2>
  	<?php printMWEditableAlbumDesc(true); ?>
</div>
<?php
 	drawWongmListSubalbums();

 	/* Only print if we have images. */
 	$num = getNumImages();
  	if ($num > 0)
  	{
  		drawWongmGridImages('album', $num);
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