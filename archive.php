<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Archive';

if (isset($_GET['page']))
	$showSingleMonth = true;
else
	$showSingleMonth = false;
	
$timeformatted = "Gallery archive";
$rssType = 'Gallery';
$rssTitle = 'Recent uploads';
$headbarextra = "";

if ($showSingleMonth)
{
	// using the PAGE param so calls from archive-date.php are not cached (resulting in only a single date ever seen)
	$month = $_GET['page'];
	$splitmonth = explode('-', $month);
	
	if (sizeof($splitmonth) == 2)
	{	
		$timeformatted = strftime('%B %Y', mktime(1, 1, 1, $splitmonth[1], 1, $splitmonth[0]));
		$pageTitle .= " - $timeformatted";
		$headbarextra = " &raquo; $timeformatted";
	}
}
include_once('header.php');
?>
<div class="headbar">
	<span id="breadcrumb"><span class="lede"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo;</span>
	<a href="<?php echo ARCHIVE_URL_PATH; ?>" title="Gallery Archive">Archive</a><?php echo $headbarextra; ?>
	</span><span id="righthead"><?php printSearchForm();?></span>
</div>
<div class="topbar">
	<h2><?php echo $timeformatted; ?></h2>
</div>
<div id="archive">
<?php
if ($showSingleMonth and $month != '' and function_exists("printSingleMonthArchive"))
{
	echo '<p>Photos by day:</p>';
	
	printSingleMonthArchive();
	
	// link to search page for all photos of this month
	echo "<p>Or <a href=\"".html_encode(getSearchURL(null, substr($month, 0, 7), null, 0, null))."\" rel=\"nofollow\">show all photos</a></p>\n";
	echo '</div>';
}
else
{
	printAllMonths('archive', 'year', 'month', 'desc');
	echo '</div>';
		
	if ($zenpage = getOption('zp_plugin_zenpage')) 
	{ 
		if(function_exists("printNewsArchive")) { ?>
<div id="archive_news"><h2><?php echo('News archive') ?></h2>
	<?php printNewsArchive("archive");	?>
</div>
<?php 	}
?>
<div id="tag_cloud">
	<h2><?php echo gettext('Popular tags'); ?></h2>
		<?php printAllTagsAs('cloud', 'tags'); ?>
</div>
<?php
	}
}
include_once('footer.php');
?>