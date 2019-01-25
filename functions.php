<?php

//******************************************************************************
// Miscellaneous functions for the ZenPhoto gallery that I need
//
// For wongm.railgeelong.com
//
// V 1.0.0
//
//******************************************************************************

require_once('functions-gallery-formatting.php');
require_once('functions-link-definitions.php');

zp_register_filter('checkPageValidity', 'wongmTheme::checkLinks');
zp_register_filter('admin_toolbox_album', 'wongmTheme::addAlbumLink');
zp_register_filter('admin_toolbox_global', 'wongmTheme::addGlobalLink');
zp_register_filter('on_this_day_additional_where', 'wongmTheme::additionalWhere');

/**
 * Plugin option handling class
 *
 */
class wongmTheme {
	
	static function checkLinks($count, $gallery_page, $page) {
    	switch (stripSuffix($gallery_page))
    	{
        	case 'archive':
        	case 'everything':
        	case 'popular-all-time':
        	case 'popular-by-ratings':
        	case 'popular-ratings':
        	case 'popular-this-month':
        	case 'popular-this-week':
        	case 'popular-this-year':
        	case 'recent-albums':
        	case 'recent-duplicates':
        	case 'recent-resize':
        	case 'recent-shrink':
        	case 'recent-shrink-albums':
        	case 'recent-uncaptioned':
        	case 'recent-uncaptioned-albums':
        	case 'recent-uploads':
        	case 'recent-wagons':
        	case 'recent-trams':
        	case 'recent-buses':
        	case 'recent-trains':
        	case 'rss-updates':
        	    return true;
		}
	}

    static function additionalWhere() {
		return EXCLUDED_IMAGE_ALBUM_SQL;
    }

    static function addAlbumLink($albumname) {
    }
    
    static function addGlobalLink() {
    	echo "<li>";
    	printLinkHTML(WEBPATH.'/page/recent-resize', 'Images to action', NULL, NULL, NULL);
    	echo "</li>";
    }
}
?>