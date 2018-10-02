<?php


	/* DB VARS */
	if($site_mode == "test")
	{
		$db_host = "127.0.0.1";
		$db_username = "dba_mtc";
		$db_password = "dba_mtc";
		$db_database = "db_mtc";
	}
	else
	{
		$db_host = "127.0.0.1";
		$db_username = "dba_mtc";
		$db_password = "dba_mtc";
		$db_database = "db_mtc";
	}


	// SALT
	$sha_salt = "5p6xx1v%Nk";

	/* DB CONNECT */
	$link = new PDO(
		"mysql:host=".$db_host.";dbname=".$db_database.";charset=utf8mb4",
		$db_username,
		$db_password,
		array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_PERSISTENT => false
		)
	);


?>