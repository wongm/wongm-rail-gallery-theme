<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

global $_zp_db;

if (isset($_GET['token']))
{
	if ($_GET['token'] == getOption('wongm_cron_security_key'))
	{
        $hiddenCaptionedImageQuery = "SELECT count(1) AS `hiddenUncaptionedImageCount` 
            FROM " . $_zp_db->prefix('images') . " i 
            WHERE i.`show` = 0 AND " . UNCAPTIONED_IMAGE_REGEX;
            
        $hiddenUncaptionedImageQuery = "SELECT count(1) AS `hiddenCaptionedImageCount` 
            FROM " . $_zp_db->prefix('images') . " i 
            WHERE i.`show` = 0 AND " . CAPTIONED_IMAGE_REGEX;
        
        $hiddenUncaptionedImageCount = $_zp_db->querySingleRow($hiddenCaptionedImageQuery)['hiddenUncaptionedImageCount'];
        $hiddenCaptionedImageCount = $_zp_db->querySingleRow($hiddenUncaptionedImageQuery)['hiddenCaptionedImageCount'];
        
        if ($hiddenUncaptionedImageCount == 0 && $hiddenCaptionedImageCount > 0)
        {
            $updateUnpublishedCaptionedImagesQuery = "UPDATE " . $_zp_db->prefix('images') . " i SET i.`show` = 1 WHERE i.`show` = 0 AND " . CAPTIONED_IMAGE_REGEX;
            $_zp_db->query($updateUnpublishedCaptionedImagesQuery);
            echo "PUBLISHED NEWLY CAPTIONED IMAGES<br>";
            
		    require_once(SERVERPATH . '/' . ZENFOLDER . '/' . PLUGIN_FOLDER . '/static_html_cache.php');
    	    static_html_cache::clearHTMLCache();
    	    echo "CACHE CLEARED<br>";
        }
        else if ($hiddenUncaptionedImageCount > 0)
        {
            echo "$hiddenUncaptionedImageCount IMAGES NEED CAPTIONS<br>";
			
			$getAlbumsToUpdate = "SELECT albumid FROM " . $_zp_db->prefix('images') . " GROUP BY albumid HAVING max(DATE(date)) = min(DATE(date))";
			$results = $_zp_db->queryFullArray($getAlbumsToUpdate);
			
			$albumIds = [];
			foreach ($results as $albumId)
			{
				array_push($albumIds, $albumId['albumid']);
			}
			
			$albumsToSetDates = "UPDATE " . $_zp_db->prefix('albums') . " SET sort_type = 'date', image_sortdirection = 0 WHERE id IN (" . implode(",", $albumIds) . ")";
			$_zp_db->query($albumsToSetDates);
			echo "UPDATED SORTING FOR " . $_zp_db->getAffectedRows() . " ALBUMS<BR>";

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