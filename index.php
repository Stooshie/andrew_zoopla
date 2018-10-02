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

	$page_title = "Home";


	// Retrieve the string we just stored to prove it was stored correctly

	$sql_select_properties = "
		SELECT
			ListingId, County, Country, Town, Description,
			FullDetailsUrl, DisplayableAddress, ImageURL, ThumbnailURL,
			Latitude, Longitude, Price, NumberOfBedrooms, NumberOfBathrooms,
			PropertyType, SaleOrRent, UpdatedByAdmin, DeletedByAdmin
		FROM
			properties
		WHERE
			DeletedByAdmin = :DeletedByAdmin
	";

	$handle_sql_select_properties = $link->prepare($sql_select_properties);
	$handle_sql_select_properties->bindValue(':DeletedByAdmin', 0, PDO::PARAM_INT);
	$handle_sql_select_properties->execute();
	// Store the result into an object that we'll output later in our HTML
	$result_sql_select_properties = $handle_sql_select_properties->fetchAll(PDO::FETCH_ASSOC);

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




<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Town</th>
      <th scope="col">Description</th>
      <th scope="col">Address</th>
      <th scope="col">Thumb</th>
      <th scope="col">Tasks</th>

    </tr>
  </thead>
  <tbody>
	<?php foreach($result_sql_select_properties as $row){ ?>
    <tr>
    	<th scope="row" class="<?= ($row["UpdatedByAdmin"]==1)?"bg-warning":"" ?>"><?= $row["ListingId"] ?></th>
    	<td><?= $row["Town"] ?></td>
    	<td title="<?= $row["Description"] ?>"><?= mb_substr($row["Description"], 0, 35); ?>...</td>
		<td><?= $row["DisplayableAddress"] ?></td>
		<?php
			if(strpos($row["ThumbnailURL"], "ttps") > 0)
			{
			?>
				<td><img src="<?= $row["ThumbnailURL"] ?>" /></td>
			<?php
			}
			else
			{
			?>
				<td><img src="<?= $www_url."uploaded_images/".$row["ThumbnailURL"] ?>" /></td>
			<?php
			}
		?>
		<td class="align-middle">
			<button class="btn btn-xs btn-success padding-xs edit-item" data-listing_id="<?= $row["ListingId"] ?>">edit</button>&nbsp;
			<button class="btn btn-xs btn-danger padding-xs delete-item" data-listing_id="<?= $row["ListingId"] ?>">delete</button></td>
    </tr>
	<?php } ?>
  </tbody>
</table>

			<button class="btn btn-xs btn-info padding-xs add-item">add</button></td>


</div><!-- /.container -->

<?php
include($www_path."_includes/foot.php");
?>

<script
	type="text/javascript">
	$(document).ready(function(){
		$(document).on("click", ".edit-item", function(event){
			event.preventDefault();
			var listing_id = $(this).data("listing_id");
			document.location = "edit.php?listing_id="+listing_id;
		});

		$(document).on("click", ".add-item", function(event){
			event.preventDefault();
			document.location = "add.php";
		});
		$(document).on("click", ".delete-item", function(event){
			event.preventDefault();
			var $listing_id = $(this).data("listing_id");
			var yorn = confirm("Are You Sure?");
			if(yorn){
				$.ajax({
					url: "ajax_delete.php?listing_id="+$listing_id,
					success: function(){
						document.location.reload();
					},
					error: function (request, error) {
						toastr["error"]("Error deleting: "+error);
					}
				});
			}
		});

	});
</script>


</body>
</html>