<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 
$pageTitle = ' - '.getImageTitle();
$rssType = 'Gallery';
$rssTitle = 'Recent uploads';
include_once('header.php'); ?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo; 
		<?php printParentBreadcrumb('', ' » ', ' » '); ?>
		<a href="<?=getAlbumURL();?>" title="<?=getAlbumTitle();?> Index"><?=getAlbumTitle();?></a>
	</span><span id="righthead"><?php echo printSearchForm(); ?></span>
</div>

<div class="topbar">
	<h2><?php printMWEditableImageTitle(true);?></h2>
	<p id="albumBreadcrumbs">In album: <a href="<?=getAlbumURL();?>" title="<?=getAlbumTitle();?> Index"><?=getAlbumTitle();?></a></p>
	<?php printMWEditableImageDesc(true); ?>
</div>

<div id="viewImage">  
	<img id="mainImage" src="<?php echo getDefaultSizedImage() ?>" alt="<?=getImageTitle();?>" width="<?=getDefaultWidth();?>" height="<?=getDefaultHeight();?>" style="margin-left: -<?=getDefaultWidth() / 2;?>px;" />
	<div id="imageOverlay" style="margin-left: -<?=getDefaultWidth() / 2;?>px; width: <?=getDefaultWidth();?>px; height: <?=getDefaultHeight();?>px;">
    <?php if (hasPrevImage()) { ?>
		<a href="<?=getPrevImageURL();?>" id="lbPrevLink" style="height: <?=getDefaultHeight();?>px;"></a>
	<?php } if (hasNextImage()) { ?>
		<a href="<?=getNextImageURL();?>" id="lbNextLink" style="height: <?=getDefaultHeight();?>px;"></a>
	<?php } ?>	
	</div>
	<p>
		<a id="showImage" href="<?=getFullImageURL();?>" rel="lightbox" title="<?=getImageTitle();?>">View full sized (<?=getFullWidth()?>px by <?=getFullHeight()?>px)</a>
	</p>
<?php 
	printEXIFData();
	printTags('links', 'Tags');
  
  ?>
</div>
<?php if (hasPrevImage() or hasNextImage()) { ?>    
<div class="pagelist"><div class="prev wrapper">
    <?php if (hasPrevImage()) { ?>
    <a id="prevLink" class="prev" href="<?=getPrevImageURL();?>" title="Previous Image"><span>&laquo;</span> Previous</a>
    <a class="prevIcon" href="<?=getPrevImageURL();?>" title="<?=getPrevImageTitle();?>"><img src="<?=getPrevImageThumb();?>" alt="<?=getPrevImageTitle();?>" /></a>
    <?php } else { echo "</td><td>"; } ?>
    </div><div class="next wrapper">
    <?php if (hasNextImage()) { ?>
    <a class="nextIcon" href="<?=getNextImageURL();?>" title="<?=getNextImageTitle();?>"><img src="<?=getNextImageThumb();?>" alt="<?=getNextImageTitle();?>"/></a>
    <a id="nextLink" class="next" href="<?=getNextImageURL();?>" title="Next Image">Next <span>&raquo;</span></a>
    <?php } else { echo "</td><td>"; } ?>
</div></div>
<?php } ?>
<?php
if(function_exists("printImageMarkupFields"))
{	
	printImageMarkupFields();
}

if (function_exists('printGoogleMap') && zp_loggedin()) {
	printGoogleMap();
}

include_once('footer.php'); 
?>