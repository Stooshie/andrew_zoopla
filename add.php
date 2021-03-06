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

	$page_title = "Edit Property";



	// IF FORM SUBMITTED
	if(!empty($_POST)){
		//print_r($_POST);













		if($_FILES['userfile']['name'] != ""){

			$currentDir = getcwd();
			$uploadDirectory = "\\uploaded_images\\";

			$errors = []; // Store all foreseen and unforseen errors here

			$fileExtensions = ['jpeg','jpg','png']; // Get all the file extensions

			$fileName = $_FILES['userfile']['name'];
			$fileSize = $_FILES['userfile']['size'];
			$fileTmpName  = $_FILES['userfile']['tmp_name'];
			$fileType = $_FILES['userfile']['type'];
			$fileExtension = strtolower(end(explode('.',$fileName)));

			$uploadPath = $currentDir . $uploadDirectory . basename($fileName); 

			//print_r($_FILES);
			//echo(basename($fileName));
			if (isset($_POST['submit'])) {

				if (! in_array($fileExtension,$fileExtensions)) {
					$errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
				}

				if ($fileSize > 2000000) {
					$errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
				}

				if (empty($errors)) {
					$didUpload = move_uploaded_file($fileTmpName, $uploadPath);

					if ($didUpload) {
						//echo "The file " . basename($fileName) . " has been uploaded";
					} else {
						echo "An error occurred somewhere. Try again or contact the admin";
					}
				} else {
					foreach ($errors as $error) {
						echo $error . "These are the errors" . "\n";
					}
				}
			}





			function imageResize($imageResourceId,$width,$height) {



				$targetWidth = 50;

				$targetHeight = 50;

				$targetLayer=imagecreatetruecolor($targetWidth,$targetHeight);

				imagecopyresampled($targetLayer,$imageResourceId,0,0,0,0,$targetWidth,$targetHeight, $width,$height);

				return $targetLayer;

			}



			$file = $uploadPath; 

			$sourceProperties = getimagesize($file);

			$fileNewName = time();

			$folderPath = $currentDir . $uploadDirectory;

			$ext = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);

			$imageType = $sourceProperties[2];

			switch ($imageType) {



				case IMAGETYPE_PNG:

					$imageResourceId = imagecreatefrompng($file); 

					$targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);

					imagepng($targetLayer,$folderPath. $fileNewName. "_thumbnail.". $ext);

					break;



				case IMAGETYPE_GIF:

					$imageResourceId = imagecreatefromgif($file); 

					$targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);

					imagegif($targetLayer,$folderPath. $fileNewName. "_thumbnail.". $ext);

					break;



				case IMAGETYPE_JPEG:

					$imageResourceId = imagecreatefromjpeg($file); 

					$targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);

					imagejpeg($targetLayer,$folderPath. $fileNewName. "_thumbnail.". $ext);

					break;



				default:

					echo "Invalid Image type.";

					exit;

					break;

			}



			move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);
			$thumb_image = $fileNewName. "_thumbnail.". $ext;

			/*
			echo($file);
			echo("<br/>");
			echo($folderPath. $fileNewName. ".". $ext);
			echo("<br/>");
			echo "Image Uploaded with created thumbnail Successfully.";
			*/


		}





		$ListingId = rand ( 100000 , 200000 );


		if($_FILES['userfile']['name'] != ""){


			$sql_property_update = "
				INSERT INTO
					properties
					(
						ListingId,
						County,
						Country,
						Town,
						Description,
						DisplayableAddress,
						ImageURL,
						ThumbnailURL,
						Price,
						NumberOfBedrooms,
						NumberOfBathrooms,
						PropertyType,
						SaleOrRent,
						PostCode,
						UpdatedByAdmin
					)
					values
					(
						:ListingId,
						:County,
						:Country,
						:Town,
						:Description,
						:DisplayableAddress,
						:ImageURL,
						:ThumbnailURL,
						:Price,
						:NumberOfBedrooms,
						:NumberOfBathrooms,
						:PropertyType,
						:SaleOrRent,
						:PostCode,
						:UpdatedByAdmin
					)
			";

			$result_listing_insert = $link->prepare($sql_property_update);
			$result_listing_insert->bindValue(':ListingId', $ListingId, PDO::PARAM_STR);
			$result_listing_insert->bindValue(':County', $_POST["County"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':Country', $_POST["Country"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':Town', $_POST["Town"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':Description', $_POST["Description"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':DisplayableAddress', $_POST["DisplayableAddress"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':ImageURL', basename($fileName), PDO::PARAM_STR);
			$result_listing_insert->bindValue(':ThumbnailURL', $thumb_image, PDO::PARAM_STR);
			$result_listing_insert->bindValue(':Price', $_POST["Price"], PDO::PARAM_INT);
			$result_listing_insert->bindValue(':NumberOfBedrooms', $_POST["NumberOfBedrooms"], PDO::PARAM_INT);
			$result_listing_insert->bindValue(':NumberOfBathrooms', $_POST["NumberOfBathrooms"], PDO::PARAM_INT);
			$result_listing_insert->bindValue(':PropertyType', $_POST["PropertyType"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':SaleOrRent', $_POST["SaleOrRent"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':PostCode', $_POST["PostCode"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':UpdatedByAdmin', 1, PDO::PARAM_STR);
			//$result_listing_insert->debugDumpParams();

			$result_listing_insert->execute();
			//var_dump($result_listing_insert->queryString);
			//echo("db");
		}
		else
		{
			$sql_property_update = "
				INSERT INTO
					properties
					(
						ListingId,
						County,
						Country,
						Town,
						Description,
						DisplayableAddress,
						Price,
						NumberOfBedrooms,
						NumberOfBathrooms,
						PropertyType,
						SaleOrRent,
						PostCode,
						UpdatedByAdmin
					)
					values
					(
						:ListingId,
						:County,
						:Country,
						:Town,
						:Description,
						:DisplayableAddress,
						:Price,
						:NumberOfBedrooms,
						:NumberOfBathrooms,
						:PropertyType,
						:SaleOrRent,
						:PostCode,
						:UpdatedByAdmin
					)
			";

			$result_listing_insert = $link->prepare($sql_property_update);
			$result_listing_insert->bindValue(':ListingId', $ListingId, PDO::PARAM_STR);
			$result_listing_insert->bindValue(':County', $_POST["County"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':Country', $_POST["Country"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':Town', $_POST["Town"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':Description', $_POST["Description"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':DisplayableAddress', $_POST["DisplayableAddress"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':Price', $_POST["Price"], PDO::PARAM_INT);
			$result_listing_insert->bindValue(':NumberOfBedrooms', $_POST["NumberOfBedrooms"], PDO::PARAM_INT);
			$result_listing_insert->bindValue(':NumberOfBathrooms', $_POST["NumberOfBathrooms"], PDO::PARAM_INT);
			$result_listing_insert->bindValue(':PropertyType', $_POST["PropertyType"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':SaleOrRent', $_POST["SaleOrRent"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':PostCode', $_POST["PostCode"], PDO::PARAM_STR);
			$result_listing_insert->bindValue(':UpdatedByAdmin', 1, PDO::PARAM_STR);
			$result_listing_insert->execute();
		}


			header("Location: index.php");
	}



?>

<!doctype html>
<html>
	<?php
	include($www_path."_includes/head.php");
	?>
    <body>


<div class="container">




<?php




/*
*/

?>

<form action="<?= $_SERVER["PHP_SELF"] ?>" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-sm-12"/><strong>Add Listing</strong></div>
	</div>




	<div class="row">
		<div class="col-sm-12"/>
		<label for="Description">Description</label>
				<textarea
					rows="7"
					class="form-control"
					id="Description"
					name="Description" required></textarea>

		</div>
	</div>

	<div class="row">
		<div class="col-sm-3">
			<label for="Town">Town</label>
			<div class="input-group">
				<input type="text" class="form-control" id="Town" name="Town" value="" placeholder="Town" required>

			</div>
		</div>
		<div class="col-sm-3">
			<label for="County">County</label>
			<div class="input-group">
				<input type="text" class="form-control" id="County" name="County" value="" placeholder="County" required>

			</div>
		</div>
		<div class="col-sm-3">
			<label for="Country">Country</label>
			<div class="input-group">
				<input type="text" class="form-control" id="Country" name="Country" value="" placeholder="Country" required>

			</div>
		</div>
		<div class="col-sm-3">
			<label for="PostCode">PostCode</label>
			<div class="input-group">
				<input type="text" class="form-control" id="PostCode" name="PostCode" value="" placeholder="PostCode" required>

			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12"/>
		<label for="Description">Displayable Address</label>
			<input
				type="text"
				class="form-control"
				id="DisplayableAddress"
				name="DisplayableAddress"
				value="" required />

		</div>
	</div>

	<div class="row">
		<div class="col-sm-12"/>
		<label for="userfile">Image Upload</label>
		<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
		<input name="userfile" type="file" id="fileToUpload" />

		</div>
	</div>

	<div class="row">

		<div class="col-sm-2"/>
			<label for="NumberOfBedrooms">Bedrooms</label>
			<select id="NumberOfBedrooms" class="form-control" name="NumberOfBedrooms">
				<?php for($i=1; $i<7; $i++){
					?>
					<option value="<?= $i ?>"><?= $i ?></option>
					<?php
				}
				?>
			</select>
		</div>

		<div class="col-sm-2"/>
			<label for="NumberOfBathrooms">Bathrooms</label>
			<select id="NumberOfBathrooms" class="form-control" name="NumberOfBathrooms">
				<?php for($i=1; $i<5; $i++){
					?>
					<option value="<?= $i ?>"><?= $i ?></option>
					<?php
				}
				?>
			</select>
		</div>

		<div class="col-sm-3"/>
		<label for="Price">Price (&pound;)</label>
		<div class="input-group">
			<input
				type="number"
				class="form-control"
				id="Price"
				name="Price"
				value="" required />
		</div>
		</div>


		<div class="col-sm-2"/>
			<label for="PropertyType">Type</label>
			<select id="PropertyType" class="form-control" name="PropertyType">
				<option value="Flat">Flat</option>
				<option value="Detached house">Detached house</option>
				<option value="Semi-detached house">Semi-detached house</option>
				<option value="Lodge">Lodge</option>
			</select>
		</div>

		<div class="col-sm-2"/>
			<label><input type="radio" name="SaleOrRent" value="sale">Sale</label>
			<label><input type="radio" name="SaleOrRent" value="rent">Rent</label>
		</div>


	</div>

	<input type="submit" name="submit" id="submit" value="Add" class="btn btn-info"/>
</form>

</div><!-- /.container -->

<?php
include($www_path."_includes/foot.php");
?>




</body>
</html>