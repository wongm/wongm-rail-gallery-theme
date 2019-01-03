<?php
$_zp_script_timer['start'] = microtime();
// force UTF-8 Ø

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!function_exists('getSummaryForCurrentDay')) {
    exit();
}

// hack to show large images
setOption('image_size', '', false);

$validationMode = isset($_GET['validation']);
if ($validationMode)
{
    $customDate=date('Y-m-d', time());
    echo "<style>img {width: 500px; }</style>";
}
else
{
    header('Content-Type: application/xml');
}

$customDate = "";
if (isset($_GET['date']))
{
    $customDate = $_GET['date'];
}
$summaryForCurrentDay = getSummaryForCurrentDay($customDate, getOption('wongm_rss_hour_threshold'));

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
<atom:link href="<?php echo $protocol; ?>://<?php echo html_encode($_SERVER["HTTP_HOST"]); ?><?php echo html_encode($_SERVER["REQUEST_URI"]); ?>" rel="self" type="application/rss+xml" />
<description><?php echo strip_tags(get_language_string(getOption('Gallery_description'), $locale)); ?></description>
<language><?php echo $validlocale; ?></language>
<pubDate><?php echo date("r", $summaryForCurrentDay->timestamp); ?></pubDate>
<lastBuildDate><?php echo date("r", $summaryForCurrentDay->timestamp); ?></lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>ZenPhoto RSS Generator</generator>
<?php
$i = 0;
while (isset($summaryForCurrentDay->yearsAgo))
{
    $domain = $protocol . '://' . $host;
    $link = "/page/on-this-day?date=$summaryForCurrentDay->currentDayLink";
    $imageEditLink = "";
    $description = "<img border=\"0\" src=\"" . $domain . $summaryForCurrentDay->imageUrl . "\" alt=\"" . $summaryForCurrentDay->title . "\" /><br>" . $summaryForCurrentDay->desc;
    if ($validationMode)
    {
        echo "<br><div>";
        $imageEditLink = " <br><a href=\"$domain$summaryForCurrentDay->imagePageUrl\">Edit image</a><br>";
        $description .= $imageEditLink;
    }
    else
    {
        $description = "<![CDATA[$description]]>";
    }
?>
<item>
    <title>On this day <?php echo $summaryForCurrentDay->title . ': ' .  $summaryForCurrentDay->desc; ?></title>
    <link><![CDATA[<?php echo $domain . $link; ?>]]></link>
    <description><?php echo $description; ?></description>
    <guid><![CDATA[<?php echo $domain . $link; ?>]]></guid>
    <pubDate><?php echo date("r", $summaryForCurrentDay->timestamp); ?></pubDate>
</item>
<?php 
    if ($validationMode && $i < 31)
    {
        $customDate=date('Y-m-d', time() + ($i * 86400));
        $summaryForCurrentDay = getSummaryForCurrentDay($customDate, getOption('wongm_rss_hour_threshold'));
        $i++;
        echo "<hr></div>";
    }
    else
    {
        $summaryForCurrentDay = null;
    }
} 
?>
</channel>
</rss>