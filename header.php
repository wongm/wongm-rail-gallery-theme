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
<link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/css/zen.css?v=4.0" type="text/css" />
<link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/css/slimbox2.css" type="text/css" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link title="<?php printGalleryTitle();?>" rel="search" type="application/opensearchdescription+xml" href="/provider.xml" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $_zp_themeroot ?>/js/slimbox2.js"></script>
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
if (isset($noIndex) && $noIndex) {
?>
<meta name="robots" content="noindex" />
<?php
}
?>
<?php zp_apply_filter("theme_head"); ?>
</head>
<body>
<?php zp_apply_filter("theme_body_open"); ?>
<div id="container">
<div id="header">
	<div id="sitename">
		<h1><a href="<?php echo HOME_PATH; ?>" title="Gallery Index"><?php echo getGalleryTitle();?></a></h1>
	</div>
	<div id="sitedesc"><?php echo getGalleryDesc();?></div>
	<div style="clear:both;"></div>
	<?php if (getOption('wongm_frontpage_mode') == 'full') { ?>
	<div class="sitemenu">
		<nav id="nav" role="navigation"> <a href="#nav" title="Show navigation">Show navigation</a> <a href="#" title="Hide navigation">Hide navigation</a>
		  <ul class="clearfix">
			<li><a href="<?php echo NEWS_URL_PATH; ?>">News</a></li>
			<li><a><span>Albums</span></a>
				  <ul>
					<li><a href="<?php echo EVERY_ALBUM_PATH; ?>" title="Show all albums">Everything</a></li>
					<li><a href="<?php echo ALBUM_THEME_PATH; ?>">Albums by theme</a></li>
					<li><a href="<?php echo RECENT_ALBUM_PATH; ?>" title="Recently added albums">Recent albums</a></li>
				  </ul>
				</li>
			<li><a title="Recently uploaded photos"><span>Recent uploads</span></a>
				  <ul>
					<li><a href="<?php echo UPDATES_URL_PATH; ?>" title="Recently uploaded photos">Everything</a></li>
					<li><a href="<?php echo TRAINS_UPDATES_URL_PATH; ?>">Trains</a></li>
					<li><a href="<?php echo TRAMS_UPDATES_URL_PATH; ?>">Trams</a></li>
					<li><a href="<?php echo BUSES_UPDATES_URL_PATH; ?>">Buses</a></li>
					<li><a href="<?php echo WAGON_UPDATES_URL_PATH; ?>">Wagons and containers</a></li>
				  </ul>
				</li>
			<li><a><span>Explore</span></a>
				  <ul>
					<li><a href="<?php echo ON_THIS_DAY_URL_PATH; ?>">On this day</a></li>
					<li><a href="<?php echo POPULAR_URL_PATH; ?>" title="Most popular photos">Popular photos</a></li>
					<li><a href="<?php echo RANDOM_ALBUM_PATH; ?>" title="A selection of random photos">Random photos</a></li>
					<li><a href="<?php echo ARCHIVE_URL_PATH; ?>">Archives</a></li>
				  </ul>
				</li>
			<li><a href="<?php echo CONTACT_URL_PATH; ?>" title="Want to drop me a line?">About me</a></li>
		  </ul>
		</nav>
	</div>
	<?php } ?>
</div>
<div id="content" class="<?php echo $contentdiv?>">