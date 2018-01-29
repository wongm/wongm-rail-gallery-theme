<?php
$_zp_script_timer['start'] = microtime();
// force UTF-8 Ø

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!function_exists('next_DailySummaryItem')) {
	exit();
}

$now = time();
if (isset($_GET['date']))
{
    $now = strtotime($_GET['date']);
}
$oneDay = new DateInterval('P1D');
$melbournetimezone = new DateTimeZone('Australia/Melbourne');
$dateToSearch = new DateTime();
$dateToSearch->setTimestamp($now);
$dateToSearch->setTimezone($melbournetimezone);

$currentHour = $dateToSearch->format('H');
if ($currentHour < getOption('wongm_rss_hour_threshold'))
{
    $dateToSearch->sub($oneDay);
}
$currentDayLink = $dateToSearch->format('Y-m-d');

$maxHitcounter = 0;
foreach (array(1, 2, 5, 10, 15) AS $year)
{
    if ($year == 1)
    {
        $suffix = " year ago";
    }
    else
    {
        $suffix = " years ago";
    }

    $pastDateToSearch = clone $dateToSearch;
    $pastDateToSearch->sub(new DateInterval('P' . $year . 'Y'));
    $dayLink = $pastDateToSearch->format('Y-m-d');

    // run the query
    setCustomPhotostream("i.date >= '$dayLink' AND i.date < '$dayLink' + INTERVAL 1 DAY AND a.folder NOT LIKE '%bus%' AND a.folder NOT LIKE '%bits%' AND a.folder != 'road-coaches' AND a.folder != 'photoshop' AND a.folder NOT LIKE 'wagons%'", "", "i.hitcounter DESC");

    // validate we have photos to show
    $photocount = getNumPhotostreamImages();
    if ($photocount > 0)
    {
        next_photostream_image();
        
        global $_zp_current_image;
        if (getHitcounter($_zp_current_image) > $maxHitcounter)
        {
            $candidate = new stdClass;
            $candidate->yearsAgo = $year . $suffix;
            $candidate->date = getImageDate();
            $candidate->pastDateToSearch = $pastDateToSearch;
            $candidate->imageUrl = getDefaultSizedImage();
            $candidate->link = "/page/on-this-day?date=$currentDayLink";
            $candidate->album = getAlbumTitleForPhotostreamImage();
            $candidate->title = getImageTitle();
            $candidate->desc = getImageDesc();
        }
    }
}

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
<pubDate><?php echo date("r", $dateToSearch->getTimestamp()); ?></pubDate>
<lastBuildDate><?php echo date("r", $dateToSearch->getTimestamp()); ?></lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>ZenPhoto RSS Generator</generator>
<?php
if (isset($candidate))
{
    $desc = $candidate->title . ". " . $candidate->desc;
    $title = "On this day $candidate->yearsAgo, " . $candidate->pastDateToSearch->format('F d Y') . ": $desc";
?>
<item>
    <title><?php echo $title; ?></title>
    <link><![CDATA[<?php echo $protocol . '://' . $host . $candidate->link; ?>]]></link>
    <description><![CDATA[<img border="0" src="<?php echo $protocol . '://' . $host . $candidate->imageUrl; ?>" alt="<?php echo $title ?>" /><br><?php echo $desc; ?>]]></description>
    <guid><![CDATA[<?php echo $protocol . '://' . $host . $candidate->link; ?>]]></guid>
    <pubDate><?php echo date("r", $dateToSearch->getTimestamp()); ?></pubDate>
</item>
<?php } ?>
</channel>
</rss>