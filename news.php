<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - News - '.getBareNewsTitle();
$contentdiv = "newscontent";
$len = strlen($pageTitle);

if (substr($pageTitle, $len-2, 1) == '-')
{
	$pageTitle = substr($pageTitle, 0, $len-3);
}
include_once('header.php');?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a>
		<?php printNewsIndexURL("News"," » "); ?>
		<?php printCurrentNewsCategory(" » "); ?>
		<?php printNewsTitle(" » "); ?>
	</span><span id="righthead"><?php echo printSearchForm(); ?></span>
</div>
<?php 

// single news article
if(is_NewsArticle()) { 
?>
<div id="sidebar">
	<?php include("sidebar.php"); ?>
</div>
<div class="topbar"><h2><?php printNewsTitle(); ?></h2></div>
<div id="news">
	<div class="newsarticle"> 
		<div class="newsarticlecredit">
			<span class="newsarticlecredit-left"><?php printNewsDate();?> | </span>
			<?php printNewsCategories(", ",gettext("Categories: "),"newscategories"); ?>
		</div>
		<?php printNewsContent(); ?>
	</div>
<?php 
// COMMENTS TEST


	drawNewsNextables();
	echo "<p id=\"hitcounter\">Viewed ".getHitcounter()." times.</p>";

} else {
// news article loop

drawNewsFrontpageNextables();
?>
<div id="sidebar">
	<?php include("sidebar.php"); ?>
</div>
<div class="topbar"><h2>News</h2></div>
<div id="news">
<?php
  while (next_news()): ;?> 
	<div class="newsarticle"> 
    	<h4><?php printNewsURL(); ?></h4>
        <div class="newsarticlecredit">
        	<span class="newsarticlecredit-left">
        	<small><?php printNewsDate();?></small>
			</span>
		</div>
    	<?php printNewsContent(false); ?>
 	</div>	
<?php
  endwhile; 
  drawNewsFrontpageNextables();
} 
?>
</div>
<?php
include_once('footer.php'); 
?>