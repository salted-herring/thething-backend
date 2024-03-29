<?php

class APIControllerCache extends DataExtension {
	
	private function getLength(&$app) {
		$len = $app->CacheLength;
		return empty($len) ? 604800 : ((int) $len);
	}
	
	public function preFetch($app, $params) {
		$query			=	Utilities::params_to_cachekey($params);
		$cache			=	SS_Cache::factory($app->ClassName);
		$cache_key		=	$app->ClassName . '_' . $app->ID . $query;
		$cached			=	$cache->load($cache_key);
		
		if (!empty($cached)) {
			$cached		=	unserialize($cached);
			$expiry		=	$cached['expiry'];
			$now		=	new DateTime();
			
			if ($expiry <= $now) {
				$cache->remove($cache_key);
				return false;
			}
			$cached['data']['cache'] = true;
			return $cached['data'];
		}
		
		return false;
	}
	
	public function postFetch($app, $params, $data) {
		$query			=	Utilities::params_to_cachekey($params);
		$cache			=	SS_Cache::factory($app->ClassName);
		$cache_key		=	$app->ClassName . '_' . $app->ID . $query;
		$cache_expiry	=	new DateTime();
		$cache_expiry	=	$cache_expiry->modify('+' . $this->getLength($app) . ' seconds');
		$cache_object	=	serialize(array(
			'data'		=>	$data,
			'expiry'		=>	$cache_expiry
		));
		$cache->save($cache_object, $cache_key);
	}
}