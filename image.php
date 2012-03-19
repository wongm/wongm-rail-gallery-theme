<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 
$pageTitle = ' - '.getImageTitle();
$rssType = 'Gallery';
$rssTitle = 'Recent uploads';
include_once('header.php'); ?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; 
		<?php printParentBreadcrumb('', ' &raquo; ', ' &raquo; '); ?>
		<a href="<?=getAlbumLinkURL();?>" title="<?=getAlbumTitle();?> Index"><?=getAlbumTitle();?></a> &raquo; 
	  	<?php printTruncatedImageTitle(true); ?>
	</td><td><?printSearchForm();?></td></tr>
</table>

<div class="topbar">
	<h2><?=getImageTitle();?></h2>
	<?php printImageDesc(true); ?>
</div>

<div id="viewImage">  
	<img id="mainImage" src="<? echo getDefaultSizedImage() ?>" alt="<?=getImageTitle();?>" width="<?=getDefaultWidth();?>" height="<?=getDefaultHeight();?>" style="margin-left: -<?=getDefaultWidth() / 2;?>px;" />
	<div style="position: relative; left: 50%; margin-left: -<?=getDefaultWidth() / 2;?>px; width: <?=getDefaultWidth();?>px; height: <?=getDefaultHeight();?>px;">
		<a href="<?=getPrevImageURL();?>" id="lbPrevLink" style="height: <?=getDefaultHeight();?>px;"></a>
		<a href="<?=getNextImageURL();?>" id="lbNextLink" style="height: <?=getDefaultHeight();?>px;"></a>
	</div>
	<p>
		<a id="showImage" href="<?=getFullImageURL();?>" rel="lightbox" title="<?=getImageTitle();?>">View full sized (<?=getFullWidth()?>px by <?=getFullHeight()?>px)</a>
	</p>	
<?php 
	printEXIFData() ; 
  
   //printjCarouselThumbNav(NULL, 250, 250,0,0,false,false);
  
  ?>
</div>
<?php if (hasPrevImage() or hasNextImage()) { ?>    
<table class="pagelist"><tr><td>
    <?php if (hasPrevImage()) { ?>
    <a id="prevLink" class="prev" href="<?=getPrevImageURL();?>" title="Previous Image"><span>&laquo;</span> Previous</a>
    </td><td>
    <a class="prevIcon" href="<?=getPrevImageURL();?>" title="<?=getPrevImageTitle();?>"><img src="<?=getPrevImageThumb();?>" alt="<?=getPrevImageTitle();?>" /></a>
    <?php } else { echo "</td><td>"; } ?>
    </td><td>
    <?php if (hasNextImage()) { ?>
    <a class="nextIcon" href="<?=getNextImageURL();?>" title="<?=getNextImageTitle();?>"><img src="<?=getNextImageThumb();?>" alt="<?=getNextImageTitle();?>"/></a>
    </td><td>
    <a id="nextLink" class="next" href="<?=getNextImageURL();?>" title="Next Image">Next <span>&raquo;</span></a>
    <?php } else { echo "</td><td>"; } ?>
</td></tr></table>
<?php } ?>
<?
if(function_exists("printImageMarkupFields"))
{	
	printImageMarkupFields();
}
include_once('footer.php'); 
?>