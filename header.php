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
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
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

// get rid of leading ' - '
if (substr($pageTitle, 0, 3) == ' - ')
{
	$pageTitle = substr($pageTitle, 3);
}

global $galleryImageAlbumCountMessage;
$galleryImageAlbumCountMessage = buildGalleryImageAlbumCountMessage();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $pageTitle; ?> - <?php printGalleryTitle(); ?></title>
<link rel="stylesheet" href="<?= $_zp_themeroot ?>/css/zen.css?v=4.51" type="text/css" />
<link rel="stylesheet" href="<?= $_zp_themeroot ?>/css/slimbox2.css" type="text/css" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link title="<?php printGalleryTitle();?>" rel="search" type="application/opensearchdescription+xml" href="/provider.xml" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?= $_zp_themeroot ?>/js/slimbox2.js"></script>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="Marcus Wong" />
<meta name="keywords" content="railways train geelong Victoria Australia photos photographs images" />
<?php
//special RSS stuff
if (isset($rssType) AND isset($rssTitle) AND strlen($rssType) > 0 AND strlen($rssTitle) > 0)
{
	printRSSHeaderLink($rssType, $rssTitle);
}
if (function_exists('printMetadata')) {
    printMetadata($pageTitle);
}
?>
<?php zp_apply_filter("theme_head"); ?>
</head>
<body>
<?php zp_apply_filter("theme_body_open"); ?>
<div id="container">
<div id="header">
	<div id="sitename">
		<h1><a href="/" title="Gallery Index"><?=getGalleryTitle();?></a></h1>
	</div>
	<div id="sitedesc"><?=getGalleryDesc();?></div>
	<div style="clear:both;"></div>
	<div class="sitemenu">
		<nav id="nav" role="navigation"> <a href="#nav" title="Show navigation">Show navigation</a> <a href="#" title="Hide navigation">Hide navigation</a>
		  <ul class="clearfix">
			<li><a href="/news">News</a></li>
			<li><a><span>Albums</span></a>
				  <ul>
					<li><a href="<?=EVERY_ALBUM_PATH?>" title="Show all albums">Everything</a></li>
					<li><a href="/page/albums">Albums by theme</a></li>
					<li><a href="<?=RECENT_ALBUM_PATH?>" title="Recently added albums">Recent albums</a></li>
				  </ul>
				</li>
			<li><a title="Recently uploaded photos"><span>Recent uploads</span></a>
				  <ul>
					<li><a href="<?=UPDATES_URL_PATH?>" title="Recently uploaded photos">Everything</a></li>
					<li><a href="/page/recent-trains">Trains</a></li>
					<li><a href="/page/recent-trams">Trams</a></li>
					<li><a href="/page/recent-buses">Buses</a></li>
					<li><a href="/page/recent-wagons">Wagons and containers</a></li>
				  </ul>
				</li>
			<li><a><span>Explore</span></a>
				  <ul>
					<li><a href="/page/on-this-day">On this day</a></li>
					<li><a href="<?=POPULAR_URL_PATH?>" title="Most popular photos">Popular photos</a></li>
					<li><a href="<?=RANDOM_ALBUM_PATH?>" title="A selection of random photos">Random photos</a></li>
					<li><a href="<?=ARCHIVE_URL_PATH?>">Archives</a></li>
				  </ul>
				</li>
			<li><a href="<?=CONTACT_URL_PATH?>" title="Want to drop me a line?">About me</a></li>
		  </ul>
		</nav>
	</div>
</div>
<div id="content" class="<?=$contentdiv?>">