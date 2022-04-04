<?php
require_once ("includes.php");

header ("Content-Type: application/json");

$response = array ();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if (check_login()) {
		$response = $users[101]['name'];
	} else {
		return_unauthorised ("You are not logged in");
	}
} else {
	return_error ("Method not supported");
}

echo json_encode ($response);
?>

