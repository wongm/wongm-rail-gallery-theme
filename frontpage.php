<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Welcome';

include_once('header.php');

global $_randomImageAttempts;

$mostRecentImageData = getMostRecentImageData();
?>
<div class="headbar">
    <span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo; 
    Home
    </span><span id="righthead"><?php echo printSearchForm(); ?></span>
</div>
<div id="indexalbums">
<?php 
$dailySummaryData = array();
if (function_exists('NewDailySummary')) 
{
    NewDailySummary(8);
    while (next_DailySummaryItem()) { 
        global $_zp_current_DailySummaryItem;
        makeImageCurrent($_zp_current_DailySummaryItem->getDailySummaryThumbImage());    
        $dayData = new stdClass;
        $dayData->date = date("l, j F", strtotime(getDailySummaryDate()));
        $dayData->imagePath = getDefaultSizedImage();
        $dayData->imageCaption = $dayData->date . ' - ' . getImageTitle();
        $dayData->description = getDailySummaryNumImages() . ' new photos ' . getDailySummaryDescInternal();
        $dayData->link = getDailySummaryUrl();
        $dailySummaryData[] = $dayData;
    }
}
?>
<div class="album recentuploads">
    <div class="albumthumb">
<?php   foreach ($dailySummaryData as $key=>$dayData)
        { ?>
        <a href="<?php echo UPDATES_URL_PATH; ?>" title="Recent uploads">
            <img src="<?php echo $dayData->imagePath; ?>" alt="<?php echo $dayData->imageCaption; ?>" title="<?php echo $dayData->imageCaption; ?>" /><br>
        </a>
<?php       if ($key >= 2)
                break;
        } ?>
    </div>
    <div class="summarydesc">
        <h2><a href="<?php echo UPDATES_URL_PATH; ?>" title="Recent uploads">Recent uploads</a></h2>
        <p><?php echo $mostRecentImageData['content']; ?></p>
        <ul>
<?php   foreach ($dailySummaryData as $dayData)
        { ?>
        <li>
            <p><?php echo $dayData->date; ?></p>
            <p><?php echo $dayData->description; ?></p>
        <?php   } ?>
        </ul>
    </div>
</div>
<?php

if (function_exists('getSummaryForCurrentDay')) {

    $customDate = "";
    if (isset($_GET['date']))
    {
        $customDate = $_GET['date'];
    }
    $summaryForCurrentDay = getSummaryForCurrentDay($customDate);
    $link = "/page/on-this-day?date=$summaryForCurrentDay->currentDayLink";
?>
<div class="album recentuploads">
    <div class="albumthumb">
        <a href="/page/on-this-day" title="On this day"><img src="<?php echo $summaryForCurrentDay->imageUrl; ?>" /></a>
    </div>
    <div class="summarydesc">
        <h2><a href="/page/on-this-day" title="On this day">On this day</a></h2>
        <p class="recent"><?php echo $summaryForCurrentDay->title; ?></p>
        <p><?php echo $summaryForCurrentDay->desc; ?></p>
    </div>
</div>
<?php
}

if (function_exists('next_news')) {

    $i = 0;
    
    while (next_news() AND $i++ < getOption('wongm_news_count')): ;?>
<div class="news">
     <div class="newsdesc">
        <h4><?php printNewsURL(); ?></h4>
        <div class="newsarticlecredit"><?php printNewsDate();?></div>
        <?php echo getNewsContent(true); ?>
    </div>
</div>
<?php
    endwhile; 
?>
<div class="news">
    <div class="newsdesc"><p><a title="See more news items" href="/news">See more news items...</a></p></div>
</div>
<?php

} // end new if

?>
</div>
<?php

include_once('footer.php');


function getThumbnailURLFromRandomImagesSet($array)
{
    if (strlen($array['folder']) > 0)
    {
        return "/cache/" . $array['folder'] . "/" . replace_filename_with_cache_thumbnail_version($array['filename']);
    }
    
    return "";
}

function getMostRecentImageData()
{
    $thresholdText = $thresholdTextMiddle = '';
    
    // options
    $alertThreshold = getOption('wongm_frontpage_alert_threshold');
    $noticeThreshold = getOption('wongm_frontpage_notice_threshold');
    $limitOnRecentImageThumbnails = getOption('photostream_images_per_page') * 2;
    
    // get most recent image date, as well as the top 24 or so images
    $mostRecentSQL = "SELECT " . prefix('images') . ".mtime AS date, " . 
                    prefix('images') . ".title, " . 
                    prefix('images') . ".filename, " . 
                    prefix('images') . ".hitcounter, " . 
                    prefix('albums') . ".folder 
                    FROM " . prefix('images') . "
                    INNER JOIN " . prefix('albums') . " ON " . prefix('images') . ".albumid = " . prefix('albums') . ".id 
                    ORDER BY " . prefix('images') . ".date DESC LIMIT 0, $limitOnRecentImageThumbnails";
    $mostRecentImageData = query_full_array($mostRecentSQL);
    $mostRecentImageTimestamp = $mostRecentImageData[0]['date'];
    
    // determine a random image from the most recent uploaded items
    $i = 0;
    do
    {
        $randomIndex = rand(0, ($limitOnRecentImageThumbnails - 1));
        $randomImage = $mostRecentImageData[$randomIndex];
        
        // ensure we exclude boring phooos
        if(!isBoringImage($randomImage['folder']))
        {
            break;
        }
        $i++;
    }
    while ($i < $limitOnRecentImageThumbnails);
    
    $thumbnailUrl = getThumbnailURLFromRandomImagesSet($randomImage);
    
    // get date difference
    $mostRecentImageDate = strtotime(Date("Y-m-d", $mostRecentImageTimestamp));
    $todayDate = strtotime(Date("Y-m-d", time()));
    $dateDiff = $todayDate - $mostRecentImageDate;
    $daysSinceUpdate = floor($dateDiff/(60*60*24));
    $formattedUpdatedDate = strftime('%A, %e %B %Y', $mostRecentImageTimestamp);
    
    $plural = "s";
    $class = "";
    
    // format text based on date difference
    if ($daysSinceUpdate < $alertThreshold)
    {
        $class = "recent";
        
        if ($daysSinceUpdate == 1)
        {
            $plural = "";
        }
    }
    
    // is it today, or a number of days?
    if ($daysSinceUpdate == 0)
    {
        $daysSinceUpdateText = "(<b>today</b>)";
    }
    else
    {
        $daysSinceUpdateText = "($daysSinceUpdate day$plural ago)";
    }
    
    $lastUpdatedText = "<p class=\"$class\">Last updated $formattedUpdatedDate $daysSinceUpdateText</p>\n";
    
    // get number of recent images
    $recentSQL = "SELECT count(date) AS date FROM " . prefix('images') . " WHERE date > DATE_ADD(CURDATE() , INTERVAL -$alertThreshold DAY)
                    UNION ALL
                    SELECT count(date) AS date FROM " . prefix('images') . " WHERE date > DATE_ADD(CURDATE() , INTERVAL -$noticeThreshold DAY)";
    $lastImage = query_full_array($recentSQL);
    $periodAlertCount = number_format($lastImage[0]['date'], 0, '.', ',');
    $periodNoticeCount = number_format($lastImage[1]['date'], 0, '.', ',');
    
    if ($periodAlertCount > 0)
    {
        $thresholdText .= "$periodAlertCount photos added in the past $alertThreshold days";
    }
    
    if ($periodNoticeCount > 0)
    {
        $thresholdTextMiddle = "$periodNoticeCount photos added in the past $noticeThreshold days";
    }
    
    if ($thresholdTextMiddle != '')
    {
        if ($thresholdText != '')
        {
            $thresholdText .= ", ";
        }
    
        $thresholdText .= $thresholdTextMiddle;
    }
    
    global $totalGalleryImageCount;
    if ($thresholdText == '')
    {
        $thresholdText .= "$totalGalleryImageCount photos sorted by when they were uploaded.";
    }
    else
    {
        $thresholdText .= ", a total of $totalGalleryImageCount photos sorted by when they were uploaded.";
    }
    
    if ($thresholdText != '')
    {
        $thresholdText = "<p>$thresholdText</p>\n";
    }
    
    return array(
        "content" => $lastUpdatedText . $thresholdText,
        "thumbnailUrl" => $thumbnailUrl
    );
}

function isBoringImage($randomFolderName)
{
    $boring = false;
    $toExclude = explode(',' , getOption('wongm_ratings_folder_exclude'));
    foreach ($toExclude as $folderNameToCheck)
    {
        if (strpos($folderNameToCheck, $randomFolderName) !== false)
        {
            $boring = true;
        }
    }
    return $boring;
}
?>