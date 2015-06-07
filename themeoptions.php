<?php

/* Plug-in for theme option handling 
 * The Admin Options page tests for the presence of this file in a theme folder
 * If it is present it is linked to with a require_once call.
 * If it is not present, no theme options are displayed.
 * 
*/

class ThemeOptions {
	
	function ThemeOptions() {
		setThemeOptionDefault('wongm_ratings_folder_exclude', 'wagons,buses,track-machines,etm-devices,platform-faces,-bits');
		setThemeOptionDefault('wongm_imagetitle_truncate_length', 40); 
		setThemeOptionDefault('random_threshold_ratings', 2); 
		setThemeOptionDefault('random_threshold_hitcounter', 40); 
		setThemeOptionDefault('wongm_randompage_count', 12); 
		setThemeOptionDefault('wongm_recentalbum_count', 24); 
		setThemeOptionDefault('wongm_news_count', 2); 
		setThemeOptionDefault('wongm_frontpage_alert_threshold', 3); 
		setThemeOptionDefault('wongm_frontpage_notice_threshold', 7); 
		setThemeOptionDefault('popular_threshold_hitcounter', 4); 
	}
	
	function getOptionsSupported() {
		return array(	gettext('Image rating: exclude albums') => array('key' => 'wongm_ratings_folder_exclude', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('CSV string with the folder titles to be excluded')),
						gettext('Image title: truncate length') => array('key' => 'wongm_imagetitle_truncate_length', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('Image title in breadcrumb: truncate to this length')),
						gettext('Frontpage random images: rating threshold') => array('key' => 'random_threshold_ratings', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('Threshold value for the frontpage random images')),
						gettext('Frontpage random images: hitcounter threshold') => array('key' => 'random_threshold_hitcounter', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('Threshold value for the frontpage random images')),
						gettext('Frontpage: news article count') => array('key' => 'wongm_news_count', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('Number of news articles on the front page')),
						gettext('Frontpage: recent image alert threshold') => array('key' => 'wongm_frontpage_alert_threshold', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('Threshold in days for the "last updated" text to be highlighted')),
						gettext('Frontpage: recent image notice threshold') => array('key' => 'wongm_frontpage_notice_threshold', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('Threshold in days for the additional image update count text')),
						gettext('Random images: how many per page') => array('key' => 'wongm_randompage_count', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('How many images should be shown on the "Random images" page')),
						gettext('Recent albums: how many per page') => array('key' => 'wongm_recentalbum_count', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('How many albums should be shown on the "Recent albums" page')),
						gettext('Popular images: hitcounter threshold') => array('key' => 'popular_threshold_hitcounter', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('Threshold value for popular images pages'))
					);
	}

	function handleOption($option, $currentValue) {
		if ($option == 'Theme_colors') {
			$theme = basename(dirname(__FILE__));
			$themeroot = SERVERPATH . "/themes/$theme/styles";
			echo '<select id="themeselect" name="' . $option . '"' . ">\n";
			generateListFromFiles($currentValue, $themeroot , '.css');
			echo "</select>\n";
		}
	}
}
?>
