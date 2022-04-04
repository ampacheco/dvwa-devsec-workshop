<?php
require_once ("includes.php");

header ("Content-Type: application/json");

$response = array ();

if ($_SERVER['REQUEST_METHOD'] == "PUT") {
	if (check_login()) {
		$decoded = get_json_body();
		if (property_exists ($decoded, "Name")) {
			$name = $decoded->Name;

			$users[101]['name'] = $name;

			$response['Name'] = $name;
		} else {
			return_error ("Missing parameter");
		}
	} else {
		return_unauthorised ("You are not logged in");
	}
} else {
	return_error ("Method not supported");
}

echo json_encode ($response);
?>

