<?php
/**
 * @file Utilities
 *
 * Generic utility functions
 * */
class UtilityFunctions {
	
	/**
	 * Take a string with new line feeds & create paragraphs.
	 * */
	public static function nl2p($string, $viewer) {
		$items = new ArrayList();
			
		foreach(explode(PHP_EOL, $string) as $item) {
			if (trim($item)) {
				$items->push(new ArrayData(array(
					'line'	=> $item
				)));
			}
		}
		
		return $viewer->customise(new ArrayData(array(
			'Paragraphs' => $items
		)))->renderWith('Paragraphs');
	}
	
	/**
	 * find the key that matches a specific pattern.
	 * Used primarily with dbo field tags.
	 *
	 * e.g. UtilityFunctions::getValidKey('/*Description/', $this->db);
	 * */
	public static function getValidKey($pattern, $arr) {
		$keys = array();
		foreach($arr as $key => $value) {
			if (preg_match($pattern, $key)){
				$keys[] = $key;
			}
		}
		
		return $keys;
	}
	
	/**
	 * Get $count words from a piece of text.
	 * */
	public static function getWords($sentence, $count = 10) {
		preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $sentence, $matches);
		return $matches[0];
	}
	
	/**
	 * Get max number of words within a character limit.
	 * */
	public static function getWordsWithinCharLimit($sentence, $limit = 150) {
		$str = '';
		$i = 1;
		
		if(strlen($sentence) < $limit) {
			return $sentence;
		}
		
		while (strlen($current = self::getWords($sentence, $i++)) < $limit) {
			$str = $current;
		}
		
		return $str;
	}
}