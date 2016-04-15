<?php
/**
 * Daily Summary template functions
 *
 * @author Marcus Wong (wongm)
 * @package plugins
 */

 /**
 * Create a new Daily Summary listing with a custom count of items
 *
 */
function NewDailySummary($maxItems) {
	global $_zp_current_DailySummary, $_zp_gallery;
	$_zp_current_DailySummary = new DailySummary($_zp_gallery, $maxItems);
}

function getDailySummaryDate($format = null) {
    global $_zp_current_DailySummaryItem;
	$d = $_zp_current_DailySummaryItem->getDateTime();
	if (empty($d) || ($d == '0000-00-00 00:00:00')) {
		return false;
	}
	if (is_null($format)) {
		return $d;
	}
	return zpFormattedDate($format, strtotime($d));
}

function getDailySummaryModifiedDate($format = null) {
    global $_zp_current_DailySummaryItem;
	$d = $_zp_current_DailySummaryItem->getModifedDateTime();
	if (empty($d) || ($d == '0000-00-00 00:00:00')) {
		return false;
	}
	if (is_null($format)) {
		return $d;
	}
	return zpFormattedDate($format, strtotime($d));
}

function getDailySummaryUrl() {
    global $_zp_current_DailySummaryItem;
    return $_zp_current_DailySummaryItem->getLink();
}

function getDailySummaryTitle() {
    global $_zp_current_DailySummaryItem;
	$imageplural = "";
	if ($_zp_current_DailySummaryItem->getNumImages() > 1) {
		$imageplural = "s";
	}
	
	return date("l, F j Y", strtotime($_zp_current_DailySummaryItem->getDateTime())) . " - " . $_zp_current_DailySummaryItem->getNumImages() . " new image$imageplural";
}

function printDailySummaryUrl($text, $title, $class = NULL, $id = NULL) {
	printLinkHTML(getDailySummaryUrl(), $text, $title, $class, $id);
}

function getDailySummaryDesc() {
    global $_zp_current_DailySummaryItem;
	$albumplural = $imageplural = "";
	if ($_zp_current_DailySummaryItem->getNumImages() > 1) {
		$imageplural = "s";
	}
	if ($_zp_current_DailySummaryItem->getNumAlbums() > 1) {
		$albumplural = "s";
	}
	return "New photo$imageplural in the " . getDailySummaryAlbumNameText() . " album$albumplural";		
}

function getDailySummaryNumImages() {
    global $_zp_current_DailySummaryItem;
    return $_zp_current_DailySummaryItem->getNumImages();
}

function printDailySummaryAlbumNameList($includeLinks = false, $listType = "ul") {
    global $_zp_current_DailySummaryItem;
    $albums = $_zp_current_DailySummaryItem->getAlbumsArray();
	
	if (count($albums) == 0) {
		return;
	}
	
	echo "<$listType class=\"DailySummaryAlbumList\">";
	foreach($albums as $folder => $albumtitle)
	{
		if ($includeLinks) {
			$rewrite = pathurlencode($folder) . '/';
			$plain = '/index.php?album=' . pathurlencode($folder);
			$albumtitle = "<a href=\"" . rewrite_path($rewrite, $plain) . "\">$albumtitle</a>";
		}
		
		echo "<li>$albumtitle</li>";
	}
	echo "</$listType>";
}

function getDailySummaryAlbumNameText($includeLinks = false) {
    global $_zp_current_DailySummaryItem;
	
	$albumcount = 1;
	$description = $albumplural = $imageplural = "";
    $albums = $_zp_current_DailySummaryItem->getAlbumsArray();
	
	foreach($albums as $folder => $albumtitle)
	{
		if ($albumcount == count($albums) AND $albumcount > 1)
		{
			$description .= " and ";
			$albumplural = "s";
		}
		else if ($albumcount > 1)
		{
			$description .= ", ";
		}
		
		if ($includeLinks) {
			$rewrite = pathurlencode($folder) . '/';
			$plain = '/index.php?album=' . pathurlencode($folder);
			$albumtitle = "<a href=\"" . rewrite_path($rewrite, $plain) . "\">$albumtitle</a>";
		}
		
		$description .= $albumtitle;
		$albumcount++;
	}
	return $description;
}

function getCustomDailySummaryThumb($size, $width = NULL, $height = NULL, $cropw = NULL, $croph = NULL, $cropx = NULL, $cropy = null, $effects = NULL) {
	global $_zp_current_DailySummaryItem;
	$thumb = $_zp_current_DailySummaryItem->getDailySummaryThumbImage();
	return $thumb->getCustomImage($size, $width, $height, $cropw, $croph, $cropx, $cropy, true, $effects);
}

function next_DailySummaryItem() {
	global $_zp_DailySummaryItems, $_zp_current_DailySummaryItem, $_zp_current_DailySummaryItem_restore, $_zp_gallery, $_zp_current_DailySummary;
	
	if (!is_object($_zp_current_DailySummary)) {
		$_zp_current_DailySummary = new DailySummary($_zp_gallery);
	}
	if (is_null($_zp_DailySummaryItems)) {
		$_zp_DailySummaryItems = $_zp_current_DailySummary->getAlbums();		
		$_zp_current_DailySummaryItem_restore = $_zp_current_DailySummaryItem;
		$_zp_current_DailySummaryItem = new DailySummaryItem(array_shift($_zp_DailySummaryItems));
		save_context();
		add_context(ZP_ALBUM);
		return true;
	} else if (empty($_zp_DailySummaryItems)) {
		$_zp_DailySummaryItems = NULL;
		$_zp_current_DailySummaryItem = $_zp_current_DailySummaryItem_restore;
		restore_context();
		return false;
	} else {
		$_zp_current_DailySummaryItem = new DailySummaryItem(array_shift($_zp_DailySummaryItems));
		return true;
	}
}

?>
