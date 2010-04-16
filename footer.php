</div>
<div id="footer">
<a href="/">Home</a> :: <a href="<?=CONTACT_URL_PATH?>">Contact</a><br/>
<?php 	//display page generation time
	// start $time = round(microtime(), 3);
$time2 = round(microtime(), 3);
$generation = str_replace('-', '', $time2 - $time);
echo "Page Generation: $generation seconds.<br/>";

if (zp_loggedin())
{
	global $_zp_query_count;
	echo "$_zp_query_count DB hits<br>";
}

echo "$photosNumber images in $albumNumber albums.<br/>";
?>
Copyright 2005 - <?=date('Y')?> &copy; <a href="http://wongm.com">Marcus Wong</a> except where otherwise noted.<br/><br/>
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