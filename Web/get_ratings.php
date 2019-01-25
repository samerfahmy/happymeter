<?php

	require_once 'defines.php';
	require_once 'errors.php';

	// only supprt GET requests
	if ($_SERVER['REQUEST_METHOD'] != 'GET') {
		return_unsupported_http_method_error();
	}

	// Open database connection
	$db_connection = new mysqli(DB_LOCATION, DB_USER, DB_PASSWORD, DB_NAME);
	if (mysqli_connect_errno($db_connection)) {
		return_database_connection_error();
	}
	
	//mysqli_set_charset('utf8');
	
	$query = "SELECT rating, update_time FROM ratings ORDER BY update_time DESC LIMIT 6";
	
	// Run query
	$result = mysqli_query($db_connection, $query);
	if (!$result) {
		return_database_query_error(mysqli_error($db_connection));
	}
	
	// Get the average rating
	$numResults = mysqli_num_rows($result);
	$totalRating = 0;
	$ratingsArray = array();
	if ($numResults > 0) {
		for ($i = 0; $i < $numResults; $i++) {
			$row = mysqli_fetch_assoc($result);
			array_push($ratingsArray, $row["rating"]);
		}
	}

	mysqli_free_result($result);
	mysqli_close($db_connection);
	
	echo json_encode($ratingsArray);
?>
