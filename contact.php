<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

$pageTitle = ' - About me';
include_once('header.php'); ?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo; 
	About me
	</span><span id="righthead"><?php echo printSearchForm(); ?></span>
</div>
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