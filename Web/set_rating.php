<?php

	require_once 'defines.php';
	require_once 'errors.php';

// String hash functions to match Java
// Source: http://stackoverflow.com/questions/8804875/php-internal-hashcode-function
function hashCode( $s )
{
    $h = 0;
    $len = strlen($s);
    for($i = 0; $i < $len; $i++)
    {
        $h = (int)(31 * $h + ord($s[$i])) & 0xffffffff;
    }

    return $h;
}

function mysqli_result($res, $row=0, $col=0){ 
    $numrows = mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}

	// only supprt GET requests
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		return_unsupported_http_method_error();
	}
	
	$username = $_POST['username'];
	$rating = $_POST['rating'];
	
	//$username = hashCode($username);

	// Open database connection
	$db_connection = mysqli_connect(DB_LOCATION, DB_USER, DB_PASSWORD, DB_NAME);
	if (mysqli_connect_errno($db_connection)) {
		return_database_connection_error();
	}
	
	//mysqli_set_charset('utf8');
	
	//Check if the value we're about to insert already exists
	$query = "SELECT COUNT(*) FROM ratings WHERE username = '$username' AND rating = '$rating'";	
	// Run query
	$result = mysqli_query($db_connection, $query);
	if (!$result) {
		return_database_query_error(mysqli_error($db_connection));
	}

	//Only insert if the value is different
	if (mysqli_result($result, 0) == 0) {

		//Save this user's rating
		$query = "INSERT INTO ratings (username, rating) VALUES ('$username', '$rating') ON DUPLICATE KEY UPDATE rating=$rating;";
		if (!mysqli_query($db_connection, $query)) {
			return_database_query_error(mysqli_error($db_connection));
		}

		//Insert the new average into the history table	
		$query = "INSERT INTO ratings_history (current_rating) SELECT AVG(rating) FROM ratings";
		if (!mysqli_query($db_connection, $query)) {
			return_database_query_error(mysqli_error($db_connection));
		}
	}

	$query = "INSERT INTO rating_log (username, rating) VALUES ('$username', '$rating')";
	if (!mysqli_query($db_connection, $query)) {
		return_database_query_error(mysqli_error($db_connection));
	}

	mysqli_free_result($result);
	mysqli_close($db_connection);

	echo "{ \"success\": \"true\" }";
?>

