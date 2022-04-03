<?php
header ("Content-Type: application/json");

$response = array ();

function return_login_failed($message) {
	$response = array ("Error" => $message);
	http_response_code (403);
	echo json_encode ($response);
	exit;
}

function return_error($message) {
	$response = array ("Error" => $message);
	http_response_code (500);
	echo json_encode ($response);
	exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ($_SERVER['CONTENT_TYPE'] == "application/json") {
		$body = file_get_contents('php://input');
		$decoded = json_decode($body);

		if (is_null ($decoded)) {
			return_error ("Invalid JSON submitted");
		}

		# Should change this to check if object key exists not array key
		if (!array_key_exists ("Username", $decoded)) {
			return_login_failed ("No username supplied");
		} else {
			if (!array_key_exists ("Password", $decoded)) {
				return_login_failed ("No password supplied");
			} else {
				if ($decoded->Username == "robin" && $decoded->Password == "Password1") {
					$response["Token"] = "ThisIsYourSecretToken";
				} else {
					return_login_failed ("Invalid credentials");
				}
			}
		}
	} else {
		return_error ("Content type not JSON");
	}
} else {
	return_error ("Method not supported");
}

echo json_encode ($response);
?>

