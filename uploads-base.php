<?php

// force UTF-8 Ø

$pageType = $popularImageText['key'];
$pageClass = $popularImageText[$pageType]['type'];
$pageTitle = " - " . $popularImageText[$pageType]['title'];

include_once('header.php'); 
require_once("functions-search.php");

$subheading = $popularImageText[$pageType]['text'];
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

if ($pageType == 'ratings')
{
	//$subheading = RATINGS_TEXT;
}


?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<?=$pageBreadCrumb?>
	</td><td id="righthead"><?printSearchForm();?></td></tr>
</table>
<div class="topbar">
	<h2><?=$subheading?></h2>
</div>

<p><? echo $popularImageText[$pageType]['text']; ?>, images <? echo getNumberCurrentDisplayedRecords(); ?> shown on this page.
<?php
if ($popularImageText[$pageType]['subtext'])
{
	echo ' ' . $popularImageText[$pageType]['subtext'];
}
?>
</p>

<div id="images">
<table class="centeredTable">
<?php
  // neater for when only 4 items
  if ($numberOfItems == 4)
  {
	  $row = 1;
  }
  else
  {
	  $row = 0;
	  $style = ' class="trio"';
  }

  while (next_photostream_image()): $column++;
	  if ($row == 0)
	  {
		  echo "<tr$style>\n";
	  }
	  
	  drawWongmImageCell($pageType);
	  
	  if ($row == 2)
	  {
		  echo "</tr>\n";
		  $row = 0;
	  }
	  else
	  {
		  $row++;
	  }
  endwhile; ?>
</table>
</div>

<?php printPhotostreamPageListWithNav("&laquo; ".gettext("Prev"), gettext("Next")." &raquo;"); ?>


<?php
include_once('footer.php'); 
?>