<?php
require_once ("includes.php");

header ("Content-Type: application/json");

$response = array ();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$decoded = get_json_body();

	# Should change this to check if object key exists not array key
	if (!property_exists ($decoded, "Username")) {
		return_unauthorised ("No username supplied");
	} else {
		if (!property_exists ($decoded, "Password")) {
			return_unauthorised ("No password supplied");
		} else {
			if ($decoded->Username == "robin" && $decoded->Password == "Password1") {
				$response["Token"] = "ThisIsYourSecretToken";
				$response['UserId'] = 101;
			} else {
				return_unauthorised ("Invalid credentials");
			}
		}
	}
} else {
	return_error ("Method not supported");
}

echo json_encode ($response);
?>

