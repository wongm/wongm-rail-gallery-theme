<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Tags';

include_once('header.php');
?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo;
	Tags
	</span><span id="righthead"><?php printSearchForm();?></span>
</div>
<div class="topbar">
	<h2><?php echo gettext('Popular tags'); ?></h2>
</div>
<div id="tag_cloud">
		<?php printAllTagsAs('cloud', 'tags'); ?>
</div>
<style>
ul.tags {
  list-style: none;
  padding-left: 0;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  line-height: 2.5rem;
  font-size: 150%;
}
ul.tags a {
  display: block;
  padding: 0.125rem 2rem;
  position: relative;
  text-decoration: none;
}
ul.tags a:hover {
  text-decoration: underline;
}
</style>
<?php
include_once('footer.php');
?>