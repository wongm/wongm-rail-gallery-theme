<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Fleetlists';
include_once('header.php');?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo;
		Fleetlists
	</span><span id="righthead"><?php echo printSearchForm(); ?></span>
</div>
<div class="topbar">
	<h2>Fleetlists</h2>
</div>

<p>A photo of every single vehicle in a given fleet.</p>
<ul>
<?php 

foreach ($popularImageText as $fleetlist)
{
	if ($fleetlist['type'] == 'fleetlist')
	{
		echo '<li><a href="' . $fleetlist['url'] . '">' . $fleetlist['title'] . '</a></li>';
	}
}
?>
</ul>
<?php
include_once('footer.php'); 
?>