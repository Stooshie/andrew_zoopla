<?php

	/**
	 * Dev Test for MTC - 02/10/2018
	 * 
	 * @package		Zoopla API Calls
	 * @author		Andrew R. Wilson
	 * 
	 */


	// SESSIONS
	session_start();

	// INITIAL CALLS
	require_once ("_includes/init.php");
	require_once ($www_path."_includes/db.php");
	header('Content-Type: text/html; charset=UTF-8');

	// Tell PHP that we're using UTF-8 strings until the end of the script
	mb_internal_encoding('UTF-8');
	// Tell PHP that we'll be outputting UTF-8 to the browser
	mb_http_output('UTF-8');


	$page_title = "Update Script";


	// ZOOMLA API

	// call
	$url = "http://api.zoopla.co.uk/api/v1/property_listings.json?postcode=DD1+1AA&area=Dundee&page_size=100&api_key=raqjr53tyfbdytqt8bc7r3h8";
	$data = file_get_contents($url); // put the contents of the file into a variable
	$json_data = json_decode($data); // decode the JSON feed

	//print_r($json_data);

	// GET LISTINGS
	$listings = $json_data->listing;



?>


<!doctype html>
<html>
	<?php
	include($www_path."_includes/head.php");
	?>
    <body>


		<div class="container">


			<?php

				//print_r($listings);

				// LOOP ROUND LISTINGS
				foreach ($listings as $listing) {

					// Store the result into an object that we'll output later in our HTML
					//$result = $handle->fetchAll(PDO::FETCH_OBJ);
					//print_r ($listing);
					echo('<br>');
					echo($listing->listing_id);
					echo('<br>');


					// check in DB for non-admin-updated listing
					$sql_select_listing = ("
						SELECT
							COUNT(*) AS num
						FROM
							properties
						WHERE
							ListingId = :ListingId
					");
					$result_select_listing = $link->prepare($sql_select_listing);
					$result_select_listing->bindValue(':ListingId', $listing->listing_id, PDO::PARAM_INT);
					$result_select_listing->execute();
					$number_of_rows = $result_select_listing->fetchColumn();

					echo($number_of_rows);

					// IF EXISTS
					if($number_of_rows >= 1){

						// CHECK FOR UPDATED BY ADMIN
						$sql_get_updated_by_admin = "
							SELECT
								UpdatedByAdmin, DeletedByAdmin
							FROM
								properties
							WHERE
								ListingId = :ListingId
						";
						$result_get_updated_by_admin = $link->prepare($sql_get_updated_by_admin);
						$result_get_updated_by_admin->bindValue(':ListingId', $listing->listing_id, PDO::PARAM_INT);
						$result_get_updated_by_admin->execute();
						$row = $result_get_updated_by_admin->fetch(PDO::FETCH_ASSOC);
						print_r($row["UpdatedByAdmin"]);

						// IF NOT UPDATED BY ADMIN
						if(
							($row["UpdatedByAdmin"] == 0) &&
							($row["DeletedByAdmin"] == 0)
						){

							// UPDATE
							$sql_listing_update = "
								UPDATE
									properties
								SET
									County = :County,
									Country = :Country,
									Town = :Town,
									Description = :Description,
									FullDetailsUrl = :FullDetailsUrl,
									DisplayableAddress = :DisplayableAddress,
									ImageURL = :ImageURL,
									ThumbnailURL = :ThumbnailURL,
									Latitude = :Latitude,
									Longitude = :Longitude,
									Price = :Price,
									NumberOfBedrooms = :NumberOfBedrooms,
									NumberOfBathrooms = :NumberOfBathrooms,
									PropertyType = :PropertyType,
									SaleOrRent = :SaleOrRent,
									PostCode = :PostCode
								WHERE
									ListingId = :ListingId
							";
							$result_listing_insert = $link->prepare($sql_listing_update);
							$result_listing_insert->bindValue(':County', $listing->county, PDO::PARAM_STR);
							$result_listing_insert->bindValue(':Country', $listing->country, PDO::PARAM_STR);
							$result_listing_insert->bindValue(':Town', $listing->post_town, PDO::PARAM_STR);
							$result_listing_insert->bindValue(':Description', $listing->description, PDO::PARAM_STR);
							$result_listing_insert->bindValue(':FullDetailsUrl', $listing->details_url, PDO::PARAM_STR);
							$result_listing_insert->bindValue(':DisplayableAddress', $listing->displayable_address, PDO::PARAM_STR);
							$result_listing_insert->bindValue(':ImageURL', $listing->image_url, PDO::PARAM_STR);
							$result_listing_insert->bindValue(':ThumbnailURL', $listing->thumbnail_url, PDO::PARAM_STR);
							$result_listing_insert->bindValue(':Latitude', strval($listing->latitude), PDO::PARAM_STR); // PARAM_STR for DECIMAL
							$result_listing_insert->bindValue(':Longitude', strval($listing->longitude), PDO::PARAM_STR); // PARAM_STR for DECIMAL
							$result_listing_insert->bindValue(':Price', $listing->price, PDO::PARAM_INT);
							$result_listing_insert->bindValue(':NumberOfBedrooms', $listing->num_bedrooms, PDO::PARAM_INT);
							$result_listing_insert->bindValue(':NumberOfBathrooms', $listing->num_bathrooms, PDO::PARAM_INT);
							$result_listing_insert->bindValue(':PropertyType', $listing->property_type, PDO::PARAM_STR);
							$result_listing_insert->bindValue(':SaleOrRent', $listing->listing_status, PDO::PARAM_STR);
							$result_listing_insert->bindValue(':PostCode', $listing->outcode, PDO::PARAM_STR);
							$result_listing_insert->bindValue(':ListingId', $listing->listing_id, PDO::PARAM_INT);
							$result_listing_insert->execute();
						}

					}
					else{
						//print_r($listing);

						// INSERT
						$sql_listing_insert = "
							INSERT INTO
								properties
								(
									ListingId, County, Country, Town, Description,
									FullDetailsUrl, DisplayableAddress, ImageURL, ThumbnailURL,
									Latitude, Longitude, Price, NumberOfBedrooms, NumberOfBathrooms,
									PropertyType, SaleOrRent, PostCode
								)
							VALUES
								(
									:ListingId, :County, :Country, :Town, :Description,
									:FullDetailsUrl, :DisplayableAddress, :ImageURL, :ThumbnailURL,
									:Latitude, :Longitude, :Price, :NumberOfBedrooms, :NumberOfBathrooms,
									:PropertyType, :SaleOrRent, :PostCode
								)
						";

						$result_listing_insert = $link->prepare($sql_listing_insert);
						$result_listing_insert->bindValue(':ListingId', $listing->listing_id, PDO::PARAM_INT);
						$result_listing_insert->bindValue(':County', $listing->county, PDO::PARAM_STR);
						$result_listing_insert->bindValue(':Country', $listing->country, PDO::PARAM_STR);
						$result_listing_insert->bindValue(':Town', $listing->post_town, PDO::PARAM_STR);
						$result_listing_insert->bindValue(':Description', $listing->description, PDO::PARAM_STR);
						$result_listing_insert->bindValue(':FullDetailsUrl', $listing->details_url, PDO::PARAM_STR);
						$result_listing_insert->bindValue(':DisplayableAddress', $listing->displayable_address, PDO::PARAM_STR);
						$result_listing_insert->bindValue(':ImageURL', $listing->image_url, PDO::PARAM_STR);
						$result_listing_insert->bindValue(':ThumbnailURL', $listing->thumbnail_url, PDO::PARAM_STR);
						$result_listing_insert->bindValue(':Latitude', strval($listing->latitude), PDO::PARAM_STR); // PARAM_STR for DECIMAL
						$result_listing_insert->bindValue(':Longitude', strval($listing->longitude), PDO::PARAM_STR); // PARAM_STR for DECIMAL
						$result_listing_insert->bindValue(':Price', $listing->price, PDO::PARAM_INT);
						$result_listing_insert->bindValue(':NumberOfBedrooms', $listing->num_bedrooms, PDO::PARAM_INT);
						$result_listing_insert->bindValue(':NumberOfBathrooms', $listing->num_bathrooms, PDO::PARAM_INT);
						$result_listing_insert->bindValue(':PropertyType', $listing->property_type, PDO::PARAM_STR);
						$result_listing_insert->bindValue(':SaleOrRent', $listing->listing_status, PDO::PARAM_STR);
						$result_listing_insert->bindValue(':PostCode', $listing->outcode, PDO::PARAM_STR);
						$result_listing_insert->execute();
					}


				}


			?>



			<img src="https://www.zoopla.co.uk/static/images/mashery/powered-by-zoopla-150x73.png" width="150" height="73" title="Property information powered by Zoopla" alt="Property information powered by Zoopla" border="0">


		</div><!-- /.container -->

		<?php
		include($www_path."_includes/foot.php");
		?>


	</body>
</html>
