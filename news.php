<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - News - '.getBareNewsTitle();
$contentdiv = "newscontent";
$len = strlen($pageTitle);

if (substr($pageTitle, $len-2, 1) == '-')
{
	$pageTitle = substr($pageTitle, 0, $len-3);
}
include_once('header.php');?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a>
		<?php printNewsIndexURL("News"," &raquo; "); ?>
		<?php printCurrentNewsCategory(" &raquo; "); ?>
		<?php printNewsTitle(" &raquo; "); ?>
	</td><td><?printSearchForm();?></td></tr>
</table>
<?php 

// single news article
if(is_NewsArticle()) { 
?>
<div id="sidebar">
	<?php include("sidebar.php"); ?>
</div>
<div class="topbar"><h2><?php printNewsTitle(); ?></h2></div>
<div id="news">
	<div class="newsarticle"> 
		<div class="newsarticlecredit"><span class="newsarticlecredit-left"><?php printNewsDate();?> | <?php echo gettext("Comments:"); ?> <?php echo getCommentCount(); ?> | </span> <?php printNewsCategories(", ",gettext("Categories: "),"newscategories"); ?></div>
		<p><?php printNewsContent(); ?></p>
	</div>
<?php 
// COMMENTS TEST


	drawNewsNextables();
	echo "<p id=\"hitcounter\">Viewed ".getHitcounter()." times.</p>";
	
if (getOption('comment_form_articles')) { ?>
				<div id="comments">
		<?php $num = getCommentCount(); echo ($num == 0) ? "" : ("<h5>".gettext("Comments")." ($num)</h5>"); ?>
			<?php while (next_comment()){  ?>
			<div class="comment">
				<div class="commentmeta">
					<span class="commentauthor"><?php printCommentAuthorLink(); ?></span> <?php gettext("says:"); ?>
				</div>
				<div class="commentbody">
					<?php echo getCommentBody();?>
				</div>
				<div class="commentdate">
					<?php echo getCommentDateTime();?>
								<?php printEditCommentLink(gettext('Edit'), ' | ', ''); ?>
				</div>
			</div>
			<?php }; ?>
						
			<?php if (zenpageOpenedForComments()) { ?>
			<div class="imgcommentform">
							<!-- If comments are on for this image AND album... -->
				<h5><?php echo gettext("Add a comment:"); ?></h5>
				<form id="commentform" action="#" method="post">
				<div><input type="hidden" name="comment" value="1" />
							<input type="hidden" name="remember" value="1" />
								<?php
								printCommentErrors();
								$stored = getCommentStored();
								?>
					<table border="0" width="100%">
						<tr>
							<td width="60px"><label for="name"><?php echo gettext("Name:"); ?></label>
							</td>
							<td><input type="text" id="name" name="name" size="40" value="<?php echo $stored['name'];?>" class="inputbox" />	(<input type="checkbox" name="anon" value="1"<?php if ($stored['anon']) echo " CHECKED"; ?> /> <?php echo gettext("don't publish"); ?>)
							</td>
						</tr>
						<tr>
							<td><label for="email"><?php echo gettext("E-Mail:"); ?></label></td>
							<td><input type="text" id="email" name="email" size="40" value="<?php echo $stored['email'];?>" class="inputbox" />
							</td>
						</tr>
						<tr>
							<td><label for="website"><?php echo gettext("Site:"); ?></label></td>
							<td><input type="text" id="website" name="website" size="40" value="<?php echo $stored['website'];?>" class="inputbox" /></td>
						</tr>
												<?php if (getOption('Use_Captcha')) {
 													$captchaCode=generateCaptcha($img); ?>
 													<tr>
 													<td><label for="code"><?php echo gettext("Enter Captcha:"); ?>
 													<img src=<?php echo "\"$img\"";?> alt="Code" align="bottom"/>
 													</label></td>
 													<td><input type="text" id="code" name="code" size="20" class="inputbox" /><input type="hidden" name="code_h" value="<?php echo $captchaCode;?>"/></td>
 													</tr>
												<?php } ?>
							<tr><td></td><td>
							<textarea name="comment" rows="6" cols="80"><?php echo $stored['comment']; ?></textarea>
							<br/>
							<input type="submit" value="<?php echo gettext('Add Comment'); ?>" class="pushbutton" /></div>
							</td></tr>
						</table>
				</form>
			</div>
		</div>

				<?php } else { echo gettext('Comments are closed.'); } ?> 

</div><?php } // comments allowed - end

} else {
// news article loop

drawNewsFrontpageNextables();
?>
<div id="sidebar">
	<?php include("sidebar.php"); ?>
</div>
<div class="topbar"><h2>News</h2></div>
<div id="news">
<?php
  while (next_news()): ;?> 
	<div class="newsarticle"> 
    	<h4><?php printNewsTitleLink(); ?></h4>
        <div class="newsarticlecredit">
        <span class="newsarticlecredit-left">
        <p><small><?php printNewsDate();?></small></p>
<?php
if(is_GalleryNewsType()) {
	echo gettext("Album:")."<a href='".getNewsAlbumURL()."' title='".getBareNewsAlbumTitle()."'> ".getNewsAlbumTitle()."</a>";
}
?>
		</div>
    	<?php printNewsContent(); ?>
    	<p><?php printNewsReadMoreLink(); ?></p>
    	<?php printCodeblock(1); ?>
 	</div>	
<?php
  endwhile; 
  drawNewsFrontpageNextables();
} 
?>
</div>
<?
include_once('footer.php'); 
?>