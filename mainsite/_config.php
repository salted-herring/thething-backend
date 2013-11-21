<?php

global $project;
$project = 'mainsite';

global $database;
$database = SS_DATABASE_NAME;
 
// Use _ss_environment.php file for configuration
require_once("conf/ConfigureFromEnv.php");

GD::set_default_quality(100);

if (Director::isLive()) {
	SS_Log::add_writer(new SS_LogEmailWriter('administration@saltedherring.com'), SS_Log::ERR);
}
