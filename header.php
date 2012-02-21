<?php

// accepts:
// $contentdiv
// $pageTitle
// $rssType
// $rssTitle

// don't display errors
$server = $_SERVER['HTTP_HOST'];
if ($server == 'y' OR $server == 'localhost' OR $server == 'wongm' OR isset($_GET['wongm']))
{
	$editablelinkforadmin = true;
	//error_reporting(E_ALL);
}
else
{
	$editablelinkforadmin = false;
	error_reporting(0);
}

//override the name of the content div if required
if (empty($contentdiv))
{
	$contentdiv = "content";
}	

require_once("functions.php");
include_once('functions-gallery-formatting.php');

$albumNumber = array_shift(query_single_row("SELECT count(*) FROM ".prefix('albums')));//getNumAlbums();
$photosArray = query_single_row("SELECT count(*) FROM ".prefix('images'));
$photosNumber = array_shift($photosArray);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php printGalleryTitle(); echo($pageTitle); ?></title>
<link rel="stylesheet" href="<?= $_zp_themeroot ?>/zen.css" type="text/css" />
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link title="<?php printGalleryTitle();?>" rel="search" type="application/opensearchdescription+xml" href="/provider.xml" />
<script type="text/javascript" src="<?= $_zp_themeroot ?>/lightbox.js"></script>
<?php zp_apply_filter("theme_head"); ?>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<meta name="author" content="Marcus Wong" />
<meta name="description" content="Photographs of trains and railway infrastructure from around Victoria, Australia" />
<meta name="keywords" content="railways train geelong Victoria Australia photos photographs images" />
<?php
//special RSS stuff
if ($rssType != "" AND $rssTitle != "")
{
	printRSSHeaderLink($rssType, $rssTitle);
}
//facebook headers for image.php
if (getImageThumb())
{
	printFacebookTag();
}
?>
</head>
<body>
<?php printAdminToolbox(); ?>
<div id="container">
<div id="header">
<div id="sitename">
	<h1><a href="/" title="Gallery Index"><?=getGalleryTitle();?></a></h1>
</div>
<div id="sitedesc"><?=getGalleryDesc();?></div>
<div style="clear:both;"></div>
<table class="buttonbar"><tr class="sitemenu">
<td class="menu"><a href="/news" title="News">News</a></td>
<td class="menu"><a href="<?=EVERY_ALBUM_PATH?>" title="Show all albums">All albums</a></td>
<td class="menu"><a href="<?=RECENT_ALBUM_PATH?>" title="Recently added albums">Recent albums</a></td>
<td class="menu"><a href="<?=UPDATES_URL_PATH?>" title="Recently uploaded photos">Recent uploads</a></td>
<td class="menu"><a href="<?=POPULAR_URL_PATH?>" title="Most popular images">Popular</a></td>
<td class="menu"><a href="<?=RANDOM_ALBUM_PATH?>" title="A selection of random photos">Random</a></td>
<td class="menu"><a href="<?=ARCHIVE_URL_PATH?>" title="View photos in the order they were taken">Archives</a></td>
<td class="menu"><a href="<?=CONTACT_URL_PATH?>" title="Want to drop me a line?">Contact me</a></td>
</tr></table>
</div>
<div id="content" class="<?=$contentdiv?>">