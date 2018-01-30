<?php
$_zp_script_timer['start'] = microtime();
// force UTF-8 Ø

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!function_exists('getSummaryForCurrentDay')) {
	exit();
}
$customDate = "";
if (isset($_GET['date']))
{
    $customDate = $_GET['date'];
}
$summaryForCurrentDay = getSummaryForCurrentDay($customDate, getOption('wongm_rss_hour_threshold'));

header('Content-Type: application/xml');
$locale = getOption('locale');
$validlocale = strtr($locale,"_","-");
$host = htmlentities($_SERVER["HTTP_HOST"], ENT_QUOTES, 'UTF-8');
$protocol = SERVER_PROTOCOL;
$albumname = "On this day";
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<title><?php echo strip_tags(get_language_string(getOption('gallery_title'), $locale)).' - '.strip_tags($albumname); ?></title>
<link><?php echo $protocol."://".$host.WEBPATH; ?></link>
<atom:link href="<?php echo $protocol; ?>://<?php echo html_encode($_SERVER["HTTP_HOST"]); ?><?php echo html_encode($_SERVER["REQUEST_URI"]); ?>" rel="self"	type="application/rss+xml" />
<description><?php echo strip_tags(get_language_string(getOption('Gallery_description'), $locale)); ?></description>
<language><?php echo $validlocale; ?></language>
<pubDate><?php echo date("r", $summaryForCurrentDay->timestamp); ?></pubDate>
<lastBuildDate><?php echo date("r", $summaryForCurrentDay->timestamp); ?></lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>ZenPhoto RSS Generator</generator>
<?php
if (isset($summaryForCurrentDay))
{
    $link = "/page/on-this-day?date=$summaryForCurrentDay->currentDayLink";
?>
<item>
    <title>On this day <?php echo $summaryForCurrentDay->title . ': ' .  $summaryForCurrentDay->desc; ?></title>
    <link><![CDATA[<?php echo $protocol . '://' . $host . $link; ?>]]></link>
    <description><![CDATA[<img border="0" src="<?php echo $protocol . '://' . $host . $summaryForCurrentDay->imageUrl; ?>" alt="<?php echo $summaryForCurrentDay->title ?>" /><br><?php echo $summaryForCurrentDay->desc; ?>]]></description>
    <guid><![CDATA[<?php echo $protocol . '://' . $host . $link; ?>]]></guid>
    <pubDate><?php echo date("r", $summaryForCurrentDay->timestamp); ?></pubDate>
</item>
<?php } ?>
</channel>
</rss>