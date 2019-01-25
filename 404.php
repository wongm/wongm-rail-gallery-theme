<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

/*
 * index.php in ROOT is where the redirect is set
 *	
 */

$numberofresults = 0;
$displaySearch = function_exists('searchOn404');

if (function_exists('redirectOn404')) {
	redirectOn404();
}

if ($displaySearch) {
	searchOn404();
	$term = getSearchTermFrom404();
}
 
$startTime = array_sum(explode(" ",microtime())); 
$pageTitle = ' - 404 Page Not Found';
include_once('header.php');
?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo; 
	404 Page Not Found
	</span><span id="righthead"><?php echo printSearchForm(); ?></span>
</div>
<div class="topbar">
  	<h2>404 Page Not Found</h2>
</div>
<h4>The gallery object you are requesting cannot be found.</h4>
<?php

if ($displaySearch) 
{
    if (wasLookingForImage()) 
    {
        $numberofresults = getNumImages();
        // will only show top images
        drawWongmGridImages('404', 3);
    }
    else
    {
        $numberofresults = getNumAlbums();
        drawIndexAlbums('404', 3);
    }
}

// fix for wording below
if ($numberofresults == 1)
{
	$wording = "Is this it? If it isn't, then you ";
}
else if ($numberofresults > 1)
{
	$wording = "Are these it? If it isn't, then you ";
}
else
{
	$wording = "You ";
}

?>
<p><?=$wording?>can use <a href="<?=SEARCH_URL_PATH?>/<?=$term?>">Search</a> to find what you are looking for. </p> 
<p>Otherwise please check you typed the address correctly. If you followed a link from elsewhere, please inform them. If the link was from this site, then <a href="<?=CONTACT_URL_PATH?>">Contact Me</a>.</p>
<?php include_once('footer.php');?>
