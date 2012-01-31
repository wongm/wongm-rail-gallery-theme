<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - CPU usage';
include_once('header.php');

?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; CPU usage
	</td><td><?printSearchForm();?></td></tr>
</table>
<div class="topbar">
	<h2>CPU usage</h2>
</div>
<style>
ul {
	width: 80%;
}
li {
	list-style: none;
	padding: 1px 5px;
	margin: 0px;
	background-color: #FAFAFA;
}
</style>
<ul>
<?
if (zp_loggedin()) 
{
	$data = array();
	
	// work out the base path
	$basepath = split("/public_html", $_SERVER['DOCUMENT_ROOT']);
	$basepath = $basepath[0];
	$filename = "$basepath/usage_logs/cpu.log";
	$fh = fopen($filename, 'r');
	
	// read file
	while ($line = fgets($fh))
	{
		$data[] = $line;
	}
	fclose($fh);
		
	for ($i = sizeof($data)-1; $i >= 0; $i--)
	{
		// remove spaces, split by dash, get last item
		$width = explode('-', str_replace(' ', '', trim($data[$i])));
		$width = $width[sizeof($width)-1];
		echo "<li style=\"width:" . $width . "\">$data[$i]</li>";
	}
?>
<ul>
<?
}
include_once('footer.php'); ?>