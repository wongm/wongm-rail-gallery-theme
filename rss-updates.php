<?php
$_zp_script_timer['start'] = microtime();
// force UTF-8 Ø

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!function_exists('next_DailySummaryItem')) {
	exit();
}

$lastModifiedImageDateSQL = "SELECT mtime FROM " . prefix('images') . " ORDER BY mtime DESC LIMIT 0, 1";
$lastModifiedImageDate = query_single_row($lastModifiedImageDateSQL)['mtime'];

header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastModifiedImageDate).' GMT', true, 200);
header('Content-Type: application/xml');
$locale = getOption('locale');
$validlocale = strtr($locale,"_","-");
$host = htmlentities($_SERVER["HTTP_HOST"], ENT_QUOTES, 'UTF-8');
$protocol = SERVER_PROTOCOL;
$albumname = "Recent updates by upload date";

NewDailySummary(getOption('RSS_items'));
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<title><?php echo strip_tags(get_language_string(getOption('gallery_title'), $locale)).' - '.strip_tags($albumname); ?></title>
<link><?php echo $protocol."://".$host.WEBPATH; ?></link>
<atom:link href="<?php echo $protocol; ?>://<?php echo html_encode($_SERVER["HTTP_HOST"]); ?><?php echo html_encode($_SERVER["REQUEST_URI"]); ?>" rel="self"	type="application/rss+xml" />
<description><?php echo strip_tags(get_language_string(getOption('Gallery_description'), $locale)); ?></description>
<language><?php echo $validlocale; ?></language>
<pubDate><?php echo date("r", $lastModifiedImageDate); ?></pubDate>
<lastBuildDate><?php echo date("r", $lastModifiedImageDate); ?></lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>ZenPhoto RSS Generator</generator>
<?php while (next_DailySummaryItem()) { 
	global $_zp_current_DailySummaryItem;
	makeImageCurrent($_zp_current_DailySummaryItem->getDailySummaryThumbImage());
	$imagePath = getDefaultSizedImage();
?>
<item>
    <title><?php echo getDailySummaryTitle(); ?></title>
    <link><![CDATA[<?php echo $protocol . '://' . $host . getDailySummaryUrl(); ?>]]></link>
    <description><![CDATA[<img border="0" src="<?php echo $protocol . '://' . $host . $imagePath; ?>" alt="<?php echo getDailySummaryTitle() ?>" /><br><?php echo getDailySummaryDesc(); ?>]]></description>
    <guid><![CDATA[<?php echo $protocol . '://' . $host . getDailySummaryUrl(); ?>]]></guid>
    <pubDate><?php echo getDailySummaryDate("%a, %d %b %Y %H:%M:%S %z"); ?></pubDate>
</item>
<?php } ?>
</channel>
</rss>