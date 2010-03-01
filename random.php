<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Random photos';
include_once('header.php'); 
require_once("functions-search.php");
require_once("functions-random.php");
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<a href="<?=RANDOM_ALBUM_PATH?>" title="Random Images">Random photos</a>
	</td><td><?printSearchForm();?></td></tr>
</table>
<?
echo "<p>A selection of random photos each time you refresh the page</p>";
drawRandomPage();
include_once('footer.php'); 