<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Archive';

if (isset($_GET['page']))
	$showSingleMonth = true;
else
	$showSingleMonth = false;
	
$timeformatted = "Gallery archive";
$rssType = 'Gallery';
$rssTitle = 'Recent uploads';

if ($showSingleMonth)
{
	// using the PAGE param so calls from archive-date.php are not cached (resulting in only a single date ever seen)
	$month = $_GET['page'];
	$splitmonth = explode('-', $month);
	
	if (is_numeric($splitmonth[0]))
	{	
		$timeformatted = strftime('%B %Y', mktime(1, 1, 1, $splitmonth[1], 1, $splitmonth[0]));
		$pageTitle .= " - $timeformatted";
		$headbarextra = " &raquo; $timeformatted";
	}
}
include_once('header.php');
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<a href="<?=ARCHIVE_URL_PATH?>" title="Gallery Archive">Archive</a><?=$headbarextra?>
	</td><td><?printSearchForm();?></td></tr>
</table>
<div class="topbar">
	<h2><?=$timeformatted?></h2>
</div>
<div id="archive">
<?	
if ($showSingleMonth and $month != '' and function_exists("printSingleMonthArchive"))
{
	printSingleMonthArchive();
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
	<h2><? echo gettext('Popular tags'); ?></h2>
		<?php printAllTagsAs('cloud', 'tags'); ?>
</div>
<?php
	}
}
include_once('footer.php');
?>