<?php if(function_exists("printAllNewsCategories")) { ?>
<div class="sidemenu">
	<h4><?php echo gettext("News articles"); ?></h4>
	<?php printAllNewsCategories(gettext("All news"),TRUE,"","menu-active"); ?>
</div>
<?php } ?>

<?php if(function_exists("printAlbumMenu")) { ?>
<div class="sidemenu">
	<h4><?php echo gettext("Gallery"); ?></h4>
	<?php 
	if(!getOption("zenpage_zp_index_news") OR !getOption("zenpage_homepage")) {
		$allalbums = "";
	} else {
		$allalbums = gettext("Gallery index");
	}
	printAlbumMenu("list",NULL,"","menu-active","submenu","menu-active",$allalbums,false,false); 
	?>
</div>
<?php } ?>

<?php 
/*if(function_exists("printPageMenu")) { ?>
<div class="sidemenu">
	<h4><?php echo gettext("Pages"); ?></h4>
	<?php	printPageMenu("list","","menu-active","submenu","menu-active"); ?>
</div>
<?php }
*/ ?>

<?php
if (getOption('RSS_album_image') || getOption('RSS_articles')) {
	?>
	<div class="sidemenu">
	<h4><?php echo gettext("RSS"); ?></h4>
		<ul>
		<?php if(!is_null($_zp_current_album)) { ?>
		<?php printRSSLink('Album', '<li>', gettext('Album RSS'), '</li>'); ?>
		<?php } ?>
			<?php printRSSLink('Gallery','<li>','Gallery', '</li>'); ?>
			<?php if(function_exists("printZenpageRSSLink")) { ?>
			<?php printZenpageRSSLink("News","","<li>",gettext("News"),'</li>'); ?>
			<?php printZenpageRSSLink("NewsWithImages","","<li>",gettext("News and Gallery"),'</li>'); ?>
			<?php } ?>
		</ul>
	</div>
	<?php
}
?>