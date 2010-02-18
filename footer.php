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
Copyright 2005 - <?=date('Y')?> &copy; Marcus Wong except where otherwise noted.<br/><br/>
<script type="text/javascript" src="http://s40.sitemeter.com/js/counter.js?site=s40wongmgalleryextras">
</script>
<noscript>
<a href="http://s40.sitemeter.com/stats.asp?site=s40wongmgalleryextras" target="_top">
<img src="http://s40.sitemeter.com/meter.asp?site=s40wongmgalleryextras" alt="Site Meter" border="0"/></a>
</noscript>
</div>
</td></tr>
</table>
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