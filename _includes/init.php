<?php

	// INI
	error_reporting(1);
	@ini_set('display_errors',1);
	@ini_set('display_startup_errors',1);
	date_default_timezone_set("Europe/London");

	//$site_mode = "test";
	$site_mode = "live";


	$www_url = "http://localhost:2222/mtc_zoopla/";
	$site_url = "http://localhost:2222/mtc_zoopla/admin/";
	//$site_path = "/htdocs/admin/";
	$www_path = "/xampp/htdocs/mtc_zoopla/";
	$site_path = "/xampp/htdocs/mtc_zoopla/admin/";

?>