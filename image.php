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
  	<h3>Image: <?=getImageTitle();?></h3>
  	<?php printImageDesc(true); ?>
  </div>
  
  <? drawWongmImageNextables(); ?>
      
  <table class="centeredTable">
	  <tr><td class="imageDisplay">
        <a href="<?=getFullImageURL();?>" rel="lightbox" title="<?=getImageTitle();?>">
        <?php printDefaultSizedImage(getImageTitle()); ?></a><br/>
        <a href="<?=getFullImageURL();?>" rel="lightbox" title="<?=getImageTitle();?>"><? getSelectedSizedThingy(); ?></a><br/>
      </td></tr>
  </table>

  <?php printEXIFData() ; ?>
<?php if (hasPrevImage() or hasNextImage()) { ?>    
  <table class="nextables"><tr id="thumbnav"><td>
    <?php if (hasPrevImage()) { ?>
    <a class="prev" href="<?=getPrevImageURL();?>" title="Previous Image"><span>&laquo;</span> Previous</a>
    </td><td>
    <a class="next" href="<?=getPrevImageURL();?>" title="<?=getPrevImageTitle();?>"><?='<img src="'.getPrevImageThumb().'" />'; ?></a>
    <?php } else { echo "</td><td>"; } ?>
    </td><td>
    <?php if (hasNextImage()) { ?>
    <a class="prev" href="<?=getNextImageURL();?>" title="<?=getNextImageTitle();?>"><?='<img src="'.getNextImageThumb().'" />'; ?></a>
    </td><td>
    <a class="next" href="<?=getNextImageURL();?>" title="Next Image">Next <span>&raquo;</span></a>
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