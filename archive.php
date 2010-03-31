<?php 

$startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 
$pageTitle = ' - Archive';

if (isset($_GET['page']))
	$showSingleMonth = true;
else
	$showSingleMonth = false;

if ($showSingleMonth)
{
	$month = $_GET['page'];
	$splitmonth = split('-', $month);
	
	if (is_numeric($splitmonth[0]))
	{	
		$timeformatted = strftime('%B %Y', mktime(1, 1, 1, $splitmonth[1], 1, $splitmonth[0]));
		$pageTitle .= " - $timeformatted";
		$headbarextra = " &raquo; $timeformatted";
	}
	else
	{
		$month = '';
	}
}
else
{
	$rssType = 'Gallery';
	$rssTitle = 'Recent uploads';
}
include_once('header.php');
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<a href="<?=ARCHIVE_URL_PATH?>" title="Gallery Archive">Archive</a><?=$headbarextra?>
	</td><td><?printSearchForm();?></td></tr>
</table>
<div id="archive">
<?	
	if ($showSingleMonth and $month != '')
	{
		printAllDays($month);
	}
	else
	{
?>
		<div id="archive"><h2><?php echo('Gallery archive') ?></h2><?php printAllMonths(); ?></div>
<?php
		if ($zenpage = getOption('zp_plugin_zenpage')) 
		{ 
?>
	<?php if(function_exists("printNewsArchive")) { ?>
	<div id="archive_news"><h2><?php echo('News archive') ?></h2><?php printNewsArchive("archive");	?></div>
<?php 	}
?>
		<div id="tag_cloud">
		<h2><? echo gettext('Popular tags'); ?></h2>
			<?php printAllTagsAs('cloud', 'tags'); ?>
		</div>
<?php 	}
?>
	</div>
<?php	
	}
	echo "</div>";
	include_once('footer.php'); 
?>