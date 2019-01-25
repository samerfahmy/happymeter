<?php
require_once 'defines.php';
require_once 'errors.php';
	// Open database connection
	$db_connection = mysqli_connect(DB_LOCATION, DB_USER, DB_PASSWORD, DB_NAME);
	if (mysqli_connect_errno($db_connection)) {
		return_database_connection_error();
	}

	$result = mysqli_query($db_connection, "SELECT * FROM ratings_history");

	if (!$result) {
		return_database_query_error(mysqli_error($db_connection));
	}

	$json_response = array();

	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {

		$row_array['update_time'] = $row['update_time'];

		//Add a normalized rating
		$row_array['current_rating'] = (3-$row['current_rating'])/2;

		array_push($json_response,$row_array);
	}

	mysqli_free_result($result);
	mysqli_close($db_connection);

	echo json_encode($json_response);
?>
