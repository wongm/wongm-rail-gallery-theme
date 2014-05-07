<?php 

/*
 * index.php in ROOT is where the redirect is set
 *	
 */
if (!defined('WEBPATH')) die();

if (function_exists('redirectOn404')) {
	redirectOn404();
}

// otherwise show the user possible results
header("HTTP/1.0 404 Not Found");
header("Status: 404 Not Found");
 
$startTime = array_sum(explode(" ",microtime())); 
$pageTitle = ' - 404 Page Not Found';
include_once('header.php');
include_once('functions-search.php');
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; 404 Page Not Found
	</td><td id="righthead"><? printSearchForm(); ?></td></tr>
</table>
<div class="topbar">
  	<h2>404 Page Not Found</h2>
</div>
<?php
echo gettext("<h4>The gallery object you are requesting cannot be found.</h4>");

if (isset($image) AND $image != '') 
{
	$term = $image;
	$image = true;
}
else if (isset($album)) 
{
	$term = $album;
	$image = false;
}

// check for images
$term  = str_replace('.jpg', '', $term);
$term  = str_replace('.JPG', '', $term);

if ($image)
{
	$numberofresults = imageOrAlbumSearch($term, 'image');
}
else
{
	$numberofresults = 0;
}

// no images results, so check for albums
if ($numberofresults == 0)
{
	$numberofresults = imageOrAlbumSearch($term, 'album');
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