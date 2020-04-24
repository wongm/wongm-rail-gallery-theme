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

// pull down V/Line images: we just want the photo
$vlineMode = isset($_GET['vline']);
$feedTitle = "On this day - " . getGalleryTitle();
if ($vlineMode)
{
    $feedTitle = "V/Line on this day";
    zp_register_filter('on_this_day_additional_where', 'rssAdditionalWhereVline');
}

// add support for validating upcoming photos
$validationMode = isset($_GET['validation']) && zp_loggedin();
if ($validationMode)
{
    $feedTitle = "Validating photos for RSS feed: " . $feedTitle;
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
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $protocol = 'https://';
}
else {
  $protocol = 'http://';
}
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<title><?php echo strip_tags($feedTitle); ?></title>
<link><?php echo $protocol . $host.WEBPATH; ?></link>
<atom:link href="<?php echo $protocol; ?><?php echo html_encode($_SERVER["HTTP_HOST"]); ?><?php echo html_encode($_SERVER["REQUEST_URI"]); ?>" rel="self" type="application/rss+xml" />
<description><?php echo strip_tags(get_language_string(getOption('Gallery_description'), $locale)); ?></description>
<language><?php echo $validlocale; ?></language>
<pubDate><?php echo date("r", $summaryForCurrentDay->timestamp); ?></pubDate>
<lastBuildDate><?php echo date("r", $summaryForCurrentDay->timestamp); ?></lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>ZenPhoto RSS Generator</generator>
<?php
if ($validationMode)
{
    $i = 0;
    while ($i <= 35)
    {
        echo "<br><div>";
        $customDate=date('Y-m-d', time() + ($i * 86400));
        $summaryForCurrentDay = getSummaryForCurrentDay($customDate, getOption('wongm_rss_hour_threshold'));
        printCurrentData($summaryForCurrentDay, $validationMode, $host, $protocol, $vlineMode);
        echo "<hr></div>";
        $i++;
    }
}
else
{
    printCurrentData($summaryForCurrentDay, $validationMode, $host, $protocol, $vlineMode);
}

function rssAdditionalWhereVline() {
    return "a.id IN (SELECT `objectid` FROM `zen_obj_to_tag` ott INNER JOIN `zen_tags` t ON ott.`tagid` = t.`id` WHERE ott.`type` = 'albums' AND t.`name` = 'vline') AND a.id NOT IN (SELECT `objectid` FROM `zen_obj_to_tag` ott INNER JOIN `zen_tags` t ON ott.`tagid` = t.`id` WHERE ott.`type` = 'albums' AND t.`name` = 'buses')";
}

function printCurrentData($summaryForCurrentDay, $validationMode, $host, $protocol, $vlineMode)
{
    if (isset($summaryForCurrentDay->yearsAgo))
    {
        $domain = $protocol . $host;
        $link = "<![CDATA[" . $domain . "/page/on-this-day?date=$summaryForCurrentDay->currentDayLink]]>";
        $guid = $link;

        // ignore URL back to this site for V/Line images: we just want the photo
        if ($vlineMode)
        {
            $link = "";
            $guid = md5($guid);
        }
        
        $imageEditLink = "";
        $description = "<img border=\"0\" src=\"" . $domain . $summaryForCurrentDay->imageUrl . "\" alt=\"" . $summaryForCurrentDay->title . "\" /><br>" . $summaryForCurrentDay->desc;
        if ($validationMode)
        {
            $imageEditLink = "<br>" . $summaryForCurrentDay->title . "<br><a href=\"$domain$summaryForCurrentDay->imagePageUrl\">Edit image</a><br>";
            $description .= $imageEditLink;
        }
        else
        {
            $description = "<![CDATA[$description]]>";
        }
?>
<item>
    <title>On this day <?php echo $summaryForCurrentDay->title . ': ' .  $summaryForCurrentDay->desc; ?></title>
    <link><?php echo $link; ?></link>
    <description><?php echo $description; ?></description>
    <guid><?php echo $guid; ?></guid>
    <pubDate><?php echo date("r", $summaryForCurrentDay->timestamp); ?></pubDate>
</item>
<?php 
    }
} 
?>
</channel>
</rss>