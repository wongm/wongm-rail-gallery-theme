<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

$pageTitle = ' - Contact me';
include_once('header.php'); ?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; Contact me
	</td><td><?printSearchForm();?></td></tr>
</table>
<?php
printContactForm();
include_once('footer.php'); 
?>