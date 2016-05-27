<?php

global $project;
$project = 'mainsite';

global $database;
$database = SS_DATABASE_NAME;

// Use _ss_environment.php file for configuration
require_once("conf/ConfigureFromEnv.php");

GD::set_default_quality(100);

Image::set_backend("OptimisedGDBackend");

Requirements::set_write_js_to_body(false);

if (Director::isLive()) {
	SS_Log::add_writer(new SS_LogEmailWriter('administration@saltedherring.com'), SS_Log::ERR);

    // // If memcached is available, use it, else fall back to file.
    // SS_Cache::add_backend(
    //     'primary_memcached',
    //     'Memcached',
    //     array(
    //         'host' => 'localhost',
    //         'port' => 11211,
    //         'persistent' => true,
    //         'weight' => 1,
    //         'timeout' => 1,
    //         'retry_interval' => 15,
    //         'status' => true,
    //         'failure_callback' => ''
    //     )
    // );
    // SS_Cache::pick_backend('primary_memcached', 'any', 10);
}

SS_Cache::set_cache_lifetime('App', 604800, 1000);
SS_Cache::set_cache_lifetime('SiteApp', 604800, 1000);
// If memcached is available, use it, else fall back to file.
/*
SS_Cache::add_backend(
	'primary_memcached',
	'Memcached',
	array(
		'host' => 'localhost',
		'port' => 11211,
		'persistent' => true,
		'weight' => 1,
		'timeout' => 1,
		'retry_interval' => 15,
		'status' => true,
		'failure_callback' => null
	)
);
SS_Cache::pick_backend('primary_memcached', 'Memcached');
SS_Cache::pick_backend('primary_memcached', 'any', 10);
*/
