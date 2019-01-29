</div>
<div id="footer">
<?php if (getOption('wongm_frontpage_mode') == 'full') { ?>
<a href="/">Home</a> :: <a href="https://www.facebook.com/wongms.rail.gallery">Facebook</a> :: <a href="https://twitter.com/wongmsrailpics">Twitter</a> :: <a href="<?php echo CONTACT_URL_PATH; ?>">Contact</a><br/>
<?php } ?>
<?php 	//display page generation time
$endTime = array_sum(explode(" ",microtime()));
$generation = str_replace('-', '', round(($endTime - $startTime), 3));
echo "Page Generation: $generation seconds.<br/>";

global $galleryImageAlbumCountMessage;
echo $galleryImageAlbumCountMessage;
?>
</br>Copyright 2005 - <?php echo date('Y')?> &copy; <a href="http://wongm.com">Marcus Wong</a> except where otherwise noted.<br/><br/>
</div>
</div>
<?php zp_apply_filter("theme_body_close"); ?>
</body>
</html>