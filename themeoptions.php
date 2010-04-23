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
	}
	
	function getOptionsSupported() {
		return array(	gettext('Image rating: exclude albums') => array('key' => 'wongm_ratings_folder_exclude', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('CSV string with the folder titles to be excluded')),
						gettext('Image title: truncate length') => array('key' => 'wongm_imagetitle_truncate_length', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('Image title in breadcrumb: truncate to this length')),
						gettext('Random images: rating threshold') => array('key' => 'random_threshold_ratings', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('Threshold value for the frontpage random images')),
						gettext('Random images: hitcounter threshold') => array('key' => 'random_threshold_hitcounter', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('Threshold value for the frontpage random images')),
						gettext('Random images: how many per page') => array('key' => 'wongm_randompage_count', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('How many images should be shown on the "Random images" page'))
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
