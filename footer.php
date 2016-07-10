</div>
<div id="footer">
<a href="/">Home</a> :: <a href="<?=CONTACT_URL_PATH?>">Contact</a><br/>
<?php 	//display page generation time
$endTime = array_sum(explode(" ",microtime()));
$generation = str_replace('-', '', round(($endTime - $startTime), 3));
echo "Page Generation: $generation seconds.<br/>";

echo buildGalleryImageAlbumCountMessage();
?>
</br>Copyright 2005 - <?=date('Y')?> &copy; <a href="http://wongm.com">Marcus Wong</a> except where otherwise noted.<br/><br/>
</div>
</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-7118921-1");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>