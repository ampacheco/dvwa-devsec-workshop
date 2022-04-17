<?php
require_once ("includes.php");

header ("Content-Type: application/json");

$response = array ();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	$user_data = check_login();

	if ($user_data) {
		$name = $user_data->Name;
		$response = array ("Name" => $user_data->Name);
	} else {
		return_unauthorised ("You are not logged in");
	}
} else {
	return_error ("Method not supported");
}

echo json_encode ($response);
?>
