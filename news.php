<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - News - '.getBareNewsTitle();
$len = strlen($pageTitle);

if (substr($pageTitle, $len-2, 1) == '-')
{
	$pageTitle = substr($pageTitle, 0, $len-3);
}
include_once('header.php');?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a>
		<?php printNewsIndexURL("News"," &raquo; "); ?>
		<?php printCurrentNewsCategory(" &raquo; Category - "); ?>
		<?php printNewsTitle(" &raquo; "); ?>
	</td><td><?printSearchForm();?></td></tr>
</table>
<div id="news">
<?php 

// single news article
if(is_NewsArticle()) { 
	drawNewsNextables();
?>
  <h4><?php printNewsTitle(); ?></h4> 
  <div class="newsarticlecredit"><span class="newsarticlecredit-left"><?php printNewsDate();?> | <?php echo gettext("Comments:"); ?> <?php echo getCommentCount(); ?> | </span> <?php printNewsCategories(", ",gettext("Categories: "),"newscategories"); ?></div>
  <?php printNewsContent(); ?>
<?php 
// COMMENTS TEST
if (getOption('zenpage_comments_allowed')) { ?>
				<div id="comments">
		<?php $num = getCommentCount(); echo ($num == 0) ? "" : ("<hr/><h4>".gettext("Comments")." ($num)</h4>"); ?>
			<?php while (next_comment()){  ?>
			<div class="comment">
				<div class="commentmeta">
					<span class="commentauthor"><?php printCommentAuthorLink(); ?></span> <?php gettext("says:"); ?>
				</div>
				<div class="commentbody">
					<?php echo getCommentBody();?>
				</div>
				<div class="commentdate">
					<?php echo getCommentDate();?>
					,
					<?php echo getCommentTime();?>
								<?php printEditCommentLink(gettext('Edit'), ' | ', ''); ?>
				</div>
			</div>
			<?php }; ?>
						
			<?php if (zenpageOpenedForComments()) { ?>
			<div class="imgcommentform">
							<!-- If comments are on for this image AND album... -->
				<hr/><h4><?php echo gettext("Add a comment:"); ?></h4>
				<form id="commentform" action="#" method="post">
				<div><input type="hidden" name="comment" value="1" />
							<input type="hidden" name="remember" value="1" />
								<?php
								printCommentErrors();
								$stored = getCommentStored();
								?>
					<table border="0">
						<tr>
							<td width="20em"><label for="name"><?php echo gettext("Name:"); ?></label>
								(<input type="checkbox" name="anon" value="1"<?php if ($stored['anon']) echo " CHECKED"; ?> /> <?php echo gettext("don't publish"); ?>)
							</td>
							<td><input type="text" id="name" name="name" size="40" value="<?php echo $stored['name'];?>" class="inputbox" />
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

				<?php } else { echo gettext('Comments are closed.'); } ?> 


</div><?php } // comments allowed - end

	drawNewsNextables();
	echo "<p>Viewed ".zenpageHitcounter('news')." times.</p>";

} else {
// news article loop

drawNewsFrontpageNextables();

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
    <p><?php printNewsContent(); ?>
    <p><?php printNewsReadMoreLink(); ?></p>
    <?php printCodeblock(1); ?></p>
    
 </div>	
<?php
  endwhile; 
  drawNewsFrontpageNextables();
} 

echo '</div>';
include_once('footer.php'); 
?>