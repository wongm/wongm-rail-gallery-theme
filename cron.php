<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

if (isset($_GET['token']))
{
    if ($_GET['token'] == getOption('wongm_cron_security_key'))
    {
        $hiddenCaptionedImageQuery = "SELECT count(1) AS `hiddenUncaptionedImageCount` 
            FROM " . prefix('images') . " i 
            WHERE i.`show` = 0 AND " . UNCAPTIONED_IMAGE_REGEX;
            
        $hiddenUncaptionedImageQuery = "SELECT count(1) AS `hiddenCaptionedImageCount` 
            FROM " . prefix('images') . " i 
            WHERE i.`show` = 0 AND " . CAPTIONED_IMAGE_REGEX;
        
        $hiddenUncaptionedImageCount = query_single_row($hiddenCaptionedImageQuery)['hiddenUncaptionedImageCount'];
        $hiddenCaptionedImageCount = query_single_row($hiddenUncaptionedImageQuery)['hiddenCaptionedImageCount'];
        
        if ($hiddenUncaptionedImageCount == 0 && $hiddenCaptionedImageCount > 0)
        {
            $updateUnpublishedCaptionedImagesQuery = "UPDATE " . prefix('images') . " i SET i.`show` = 1 WHERE i.`show` = 0 AND " . CAPTIONED_IMAGE_REGEX;
            query($updateUnpublishedCaptionedImagesQuery);
            echo "PUBLISHED NEWLY CAPTIONED IMAGES<br>";
            
            require_once(SERVERPATH . '/' . ZENFOLDER . '/' . PLUGIN_FOLDER . '/static_html_cache.php');
            static_html_cache::clearHTMLCache();
            echo "CACHE CLEARED<br>";
        }
        else if ($hiddenUncaptionedImageCount > 0)
        {
            echo "$hiddenUncaptionedImageCount IMAGES NEED CAPTIONS<br>";
            
            $getAlbumsToUpdate = "SELECT albumid FROM " . prefix('images') . " GROUP BY albumid HAVING max(DATE(date)) = min(DATE(date))";
            $results = query_full_array($getAlbumsToUpdate);
            
            $albumIds = [];
            foreach ($results as $albumId)
            {
                array_push($albumIds, $albumId['albumid']);
            }
            
            $albumsToSetDates = "UPDATE " . prefix('albums') . " SET sort_type = 'date', image_sortdirection = 0 WHERE id IN (" . implode(",", $albumIds) . ")";
            query($albumsToSetDates);
            echo "UPDATED SORTING FOR " . db_affected_rows() . " ALBUMS<BR>";

        }
        else
        {
            echo "NO NEW IMAGES TO PUBLISH<br>";
        }
        
        if (function_exists('updateHitcounterDates'))
        {
            updateHitcounterDates();
            echo "UPDATE HITCOUNTER<BR>";
        }
        
        echo "DONE!";
        exit;
    }
}

header('HTTP/1.0 403 Forbidden');
exit;
?>