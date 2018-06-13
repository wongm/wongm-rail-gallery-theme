<?php

// force UTF-8 Ø

$pageType = '';
$pageClass = '';
$pageTitle = '';
$subheading = '';

if(isset($popularImageText['key'])) {
	$pageType = $popularImageText['key'];
	
	if(isset($popularImageText[$pageType]['type'])) {
		$pageClass = $popularImageText[$pageType]['type'];
	}
	
	if(isset($popularImageText[$pageType]['title'])) {
		$pageTitle = " - " . $popularImageText[$pageType]['title'];
	}
	
	if(isset($popularImageText[$pageType]['text'])) {
		$subheading = $popularImageText[$pageType]['text'];
	} else if(isset($popularImageText[$pageType]['title'])) {
		$subheading = $popularImageText[$pageType]['title'];
	}
}

include_once('header.php'); 

$totalImages = getNumPhotostreamImages();

switch ($pageClass)
{
	case 'popular':
		$pageBreadCrumb = '<a href="' . POPULAR_URL_PATH . '">Popular photos</a> &raquo; <a href="' . $popularImageText[$pageType]['url'] . '">' . $popularImageText[$pageType]['text'] . '</a>';
		break;
	case 'recent':
		$pageBreadCrumb = '<a href="' . UPDATES_URL_PATH . '">Recent uploads</a> &raquo; <a href="' . $popularImageText[$pageType]['url'] . '">' . $popularImageText[$pageType]['text'] . '</a>';
		break;
	default:
		$pageBreadCrumb = '<a href="' . UPDATES_URL_PATH . '">Recent uploads</a>';
		break;
}
?>
<div class="headbar">
	<span id="breadcrumb"><span class="lede"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo;</span>
	<?=$pageBreadCrumb?>
	</span><span id="righthead"><?php echo printSearchForm(); ?></span>
</div>
<div class="topbar">
	<h2><?=$subheading?></h2>
</div>

<p><?php echo $subheading; ?>, images <?php echo getNumberCurrentDisplayedRecords(); ?> shown on this page.
<?php
if (array_key_exists('subtext', $popularImageText[$pageType]))
{
	echo ' ' . $popularImageText[$pageType]['subtext'];
}
?>
</p>

<div id="imagewrapper">
	<div id="images">
<?php  
  $column = 0;

  while (next_photostream_image()): $column++;	  
	  drawWongmImageCell($pageType);
  endwhile; ?>
	</div>
</div>
<?php printPhotostreamPageListWithNav("« ".gettext("Prev"), gettext("Next")." »"); ?>
<?php
include_once('footer.php'); 
?>