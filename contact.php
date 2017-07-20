<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

$pageTitle = ' - About me';
include_once('header.php'); ?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; About me
	</td><td><?printSearchForm();?></td></tr>
</table>
<style>
#mailform { 
    border: 1px solid grey;
    padding: 10px;
}
label { display: block }
</style>
<?php
$page = new ZenpagePage('About-Me');
echo $page->getContent();
printContactForm();
include_once('footer.php'); 
?>