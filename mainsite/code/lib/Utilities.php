<?php
/**
 * @file Utilities
 *
 * Generic utility functions
 * */

class Utilities {
	public static function sanitiseClassName($string, $replacement = '') {
   		$string = str_replace(' ', '-', strtolower($string));
   		return preg_replace('/[^A-Za-z0-9\-]/', $replacement, $string);
	}
	
	public static function params_to_cachekey($params){
		$str = '';
		if (count($params) > 0) {
			foreach ($params as $name => $value) {
				$value = self::sanitiseClassName($value);
				$str .= $name . '__' . $value . '_';
			}
		
			$str = '__' . rtrim($str, '_');
		}
		return $str;
	}
	
	public static function paramStringify($params, $prefix = '') {
		$str = '';
		if (count($params) > 0) {
			foreach ($params as $name => $value) {
				$value = str_replace(' ', '+', $value);
				$str .= $name . '=' . $value . '&';
			}
		
			$str = $prefix . rtrim($str, '&');
		}
		return $str;
	}
}

class Debugger {
	public static function inspect($obj, $die = true) {
		Debug::dump($obj);
		if ($die) die;
	}
	
	public static function methods(&$obj) {
		
		if (!empty($obj)){
			echo '<pre>';
			print_r(get_class_methods($obj));
			echo '</pre>';
		}else{
			echo 'object is empty';
		}
		
		die;
	}
}

class UtilityFunctions {
	
	public static function get_emails($groupCode) {
		$group = DataObject::get_one('Group', "Code = '" . $groupCode . "'");
		
		if ($group) {
			return $group->Members()->column('Email');
		}
		
		return false;
	}
	
	public static function valid_email($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	public static function member_exist($email) {
		$member = DataObject::get_one("Member",  "Email = '".$email."'");
		return !empty($member);
	}
	
	public static function isBrowser() {
		// Regular expression to match common browsers
		$browserlist = '/(opera|aol|msie|firefox|chrome|konqueror|safari|netscape|navigator|mosaic|lynx|amaya|omniweb|avant|camino|flock|seamonkey|mozilla|gecko)+/i';
		
		$validBrowser = preg_match($browserlist, strtolower($_SERVER['HTTP_USER_AGENT'])) === 1;
		
		return $validBrowser;// && !empty($_SERVER['HTTP_REFERER']);
	}
	
	public static function CreateTagSlug($Slug, $ID) {
		$Slug = preg_replace('/[^A-Za-z0-9]+/','-', strtolower($Slug));
		
		$count = 2;
		while(DataObject::get_one('Tag', "Slug = '" . $Slug ."' AND Tag.ID != " . $ID)) {
			$Slug = preg_replace('/-[0-9]+$/', null, $Slug) . '-' . $count;
            $count++;
		}
		
		return $Slug;
	}	
	
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