<?php
require_once ("includes.php");

header ("Content-Type: application/json");

$response = array ();

if ($_SERVER['REQUEST_METHOD'] == "PUT") {
	if (check_login()) {
		$decoded = get_json_body();
		if (property_exists ($decoded, "Name") &&
			property_exists ($decoded, "UserId") && is_numeric ($decoded->UserId)) {
			$user_id = intval ($decoded->UserId);
			$name = $decoded->Name;

			update_profile($user_id, $name);

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

