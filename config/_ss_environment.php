<?php
	/* What kind of environment is this: development, test, or live (ie, production)? */
	define('SS_ENVIRONMENT_TYPE', 'dev');
	 
	/* Database connection */
	define('SS_DATABASE_SERVER', 'localhost');
	define('SS_DATABASE_USERNAME', 'silverstripe');
	define('SS_DATABASE_PASSWORD', 'nU3asT52uwUb');
	define('SS_DATABASE_NAME', 'ss_saltedherring_ss_base');
	 
	/* Configure a default username and password to access the CMS on all sites in this environment. */
	define('SS_DEFAULT_ADMIN_USERNAME', 'defaultadmin');
	define('SS_DEFAULT_ADMIN_PASSWORD', 'passw0rd');
	
	// root path
	define('ROOT', realpath('../') . '/');