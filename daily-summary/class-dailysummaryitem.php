<?php

class DailySummaryItem extends Album {
	/**
	 * Constructor for DailySummaryItem
	 *
	 * @param object &$gallery The parent gallery
	 * @param string $albumData Array data for this Album, from the earlier Photostream DB query
	 * @return Album
	 */
	function __construct($dateValue) {
		
		$this->linkname = $dateValue;
		
		$d1 = $dateValue." 00:00:00";
		$d2 = $dateValue." 23:59:59";
		
		$imageSql = "SELECT i.filename, i.date, i.mtime, i.title AS thumbtitle, a.folder AS folder, a.title AS albumtitle, a.show AS album_show, a.dynamic AS album_dynamic
			FROM ". prefix('images') ." AS i
			INNER JOIN ". prefix('albums') ." AS a ON i.albumid = a.id
			WHERE i.`date` >= \"$d1\" AND i.`date` < \"$d2\" 
			ORDER BY i.daily_score DESC, i.hitcounter DESC";

		$results = query_full_array($imageSql);
		
		if (sizeof($results) == 0)
		{
			$this->set('albums', array ());
			return;
		}
			
		foreach($results as $album)
		{
			$folder = $album['folder'];
			$text = $album['albumtitle'];
			$text = get_language_string($text);
			$text = zpFunctions::unTagURLs($text);
			$imageAlbums[$folder] = $text;
		}
		
		$albums = array_unique($imageAlbums);
		$albumCount = count($albums);
		$imagecount = count($results);
		
		$this->set('date', dateTimeConvert($results[0]['date']));
		$this->set('mtime', strftime('%Y-%m-%d %H:%M:%S', $results[0]['mtime']));
		$this->set('albums', $albums);
		$this->set('imagecount', $imagecount);
		$this->set('albumcount', $albumCount);
		$this->set('thumbfolder', $results[0]['folder']);
		$this->set('thumbimage', $results[0]['filename']);
		$this->set('thumbtitle', $results[0]['thumbtitle']);
	}
	
	function getNumImages() {
		return $this->get('imagecount');
	}
	
	function getNumAlbums() {
		return $this->get('albumcount');
	}
	
	function getAlbumNames() {
		return array_values($this->get('albums'));
	}
	
	function getAlbumsArray() {
		return $this->get('albums');
	}
	
	// overloaded functions inherited from Album
	// these ones do stuff
	function getAlbums($page = 0, $sorttype = NULL, $direction = NULL, $care = true, $mine = NULL) {
		return array_keys($this->get('albums'));		
	}
	
	function getDateTime() {
		return ($this->get('date'));
	}
	
	function getModifedDateTime() {
		return ($this->get('mtime'));
	}
	
	function getLink($page = NULL) {
		return getSearchURL('', $this->linkname, '', 0, NULL);
	}
	
	function getDailySummaryThumbImage() {
		if (!is_null($this->albumthumbnail)) {
			return $this->albumthumbnail;
		}		
		$this->albumthumbnail = newImage(newAlbum($this->get('thumbfolder')), $this->get('thumbimage'));
		return $this->albumthumbnail;
	}
	
	// don't want these ones to do anything
	function save() {}
	function loadFileNames($dirs=false) {}
}
?>