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

require_once(dirname(__FILE__).'/daily-summary/class-dailysummary.php');
require_once(dirname(__FILE__).'/daily-summary/class-dailysummaryitem.php');
require_once(dirname(__FILE__).'/daily-summary/dailysummary-template-functions.php');

?>