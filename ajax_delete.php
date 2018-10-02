<?php
	/* SESSIONS */
	session_start();

	/* INIT */
	require_once ("_includes/init.php");

	/* DB */
	require_once ($www_path."_includes/db.php");


	header('Content-Type: text/html; charset=UTF-8');

	// Tell PHP that we're using UTF-8 strings until the end of the script
	mb_internal_encoding('UTF-8');

	// Tell PHP that we'll be outputting UTF-8 to the browser
	mb_http_output('UTF-8');
	$sql_listing_delete = "
		UPDATE
			properties
		SET
			DeletedByAdmin = :DeletedByAdmin
		WHERE
			ListingId = :ListingId
	";
	$result_listing_delete = $link->prepare($sql_listing_delete);
	$result_listing_delete->bindValue(':DeletedByAdmin', 1, PDO::PARAM_STR);
	$result_listing_delete->bindValue(':ListingId', $_GET["listing_id"], PDO::PARAM_STR);
	$result_listing_delete->execute();
?>1