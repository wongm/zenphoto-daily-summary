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

function getDailySummaryDate() {
    global $_zp_current_DailySummaryItem;
    return $_zp_current_DailySummaryItem->getDateTime();
}

function getDailySummaryUrl() {
    global $_zp_current_DailySummaryItem;
    return $_zp_current_DailySummaryItem->getLink();
}

function printDailySummaryUrl($text, $title, $class = NULL, $id = NULL) {
	printLinkHTML(getDailySummaryUrl(), $text, $title, $class, $id);
}

function getDailySummaryModifiedDate() {
    global $_zp_current_DailySummaryItem;
    return $_zp_current_DailySummaryItem->getModifedDateTime();
}

function getDailySummaryDescription() {
    global $_zp_current_DailySummaryItem;
    return $_zp_current_DailySummaryItem->getDesc();
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
