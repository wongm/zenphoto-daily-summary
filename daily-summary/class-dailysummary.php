<?php

class DailySummary extends Gallery {
	var $maxItems;
	
	/**
	 * Constructor for DailySummary
	 *
	 * @param object &$gallery The parent gallery
	 * @return DailySummary
	 */
	function __construct(&$gallery, $maxItems=10) {
		if (!is_object($gallery) || strtolower(get_class($gallery)) != 'gallery') {
			debugLogBacktrace('Bad gallery in instantiation of DailySummary');
			$gallery = $gallery;
		}	
		$this->maxItems = $maxItems;
	}
		
	function loadAlbumNames() {
		$cleandates = array();
		$sql = "SELECT `date` FROM ". prefix('images');
		if (!zp_loggedin()) {
			$sql .= " WHERE `show` = 1";
		}
		$hidealbums = getNotViewableAlbums();
		if (!is_null($hidealbums)) {
			if (zp_loggedin()) {
				$sql .= ' WHERE ';
			} else {
				$sql .= ' AND ';
			}
			foreach ($hidealbums as $id) {
				$sql .= '`albumid`!='.$id.' AND ';
			}
			$sql = substr($sql, 0, -5);
		}
		
		$result = query_full_array($sql);
		foreach($result as $row){
			if (!empty($row['date'])) {
				$cleandates[] = substr($row['date'], 0, 10);
			}
		}
		$datecount = array_count_values($cleandates);
		krsort($datecount);
		
		// bake out found image data
		$i = 0;

		$albums = array();
		foreach ($datecount as $dateValue=>$numberOfImages ) {
			if ($i == $this->maxItems) {
				break;
			}			
			$albums[] = $dateValue;
			$i++;
		}		
		return $albums;		
	}
	
	function getAlbums($page = 0, $sorttype = NULL, $direction = NULL, $care = true, $mine = NULL) {
		if (is_null($this->albums)) {
			$this->albums = $this->loadAlbumNames();
		}
		return $this->albums;
	}	
}
?>