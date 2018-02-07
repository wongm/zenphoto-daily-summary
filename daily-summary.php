<?php
/**
 * Daily Summary
 *
 * Generates a set of dynamic albums containg the summary of all photos taken that day
 *
 * @author Marcus Wong (wongm)
 * @package plugins
 */

$plugin_description = gettext("Generates a set of dynamic albums containg the summary of all photos taken that day.");
$plugin_author = "Marcus Wong (wongm)";
$plugin_version = '1.0.0';
$plugin_URL = "https://github.com/wongm/zenphoto-daily-summary";
$plugin_is_filter = 500 | THEME_PLUGIN | ADMIN_PLUGIN;
$plugin_disable = !extensionEnabled('photostream') ? gettext('<em>photostream</em> plugin is required.') : false;

require_once(dirname(__FILE__).'/daily-summary/class-dailysummary.php');
require_once(dirname(__FILE__).'/daily-summary/class-dailysummaryitem.php');
require_once(dirname(__FILE__).'/daily-summary/dailysummary-template-functions.php');

zp_register_filter('admin_utilities_buttons', 'dailySummaryConfig::button');

class dailySummaryConfig {
	
	static function button($buttons) {
		$buttons[] = array(
						'category'		 => gettext('Admin'),
						'enable'			 => true,
						'button_text'	 => gettext('Daily summaries'),
						'formname'		 => 'zenphotoCaption_button',
						'action'			 => WEBPATH.'/plugins/daily-summary',
						'icon'				 => 'images/pencil.png',
						'title'				 => gettext('Configure what images will be displayed in daily summaries.'),
						'alt'					 => '',
						'hidden'			 => '',
						'rights'			 => ALBUM_RIGHTS
		);
		return $buttons;
	}
}

/*

ALTER TABLE `zen_images` ADD `daily_score` INT NULL DEFAULT NULL

*/


?>