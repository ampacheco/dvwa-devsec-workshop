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

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	# Should change this to check if object key exists not array key
	if (!array_key_exists ("HTTP_AUTHORIZATION", $_SERVER)) {
		return_login_failed ("No Authorization header supplied");
	} else {
		$header = $_SERVER["HTTP_AUTHORIZATION"];
		if (preg_match ("/^bearer (.*)$/i", $header, $matches)) {
			if (count($matches) == 2) {
				$token = $matches[1];
				if ($token == "ThisIsYourSecretToken") {
					$response = "Secret data!!!";
				} else {
					return_login_failed ("Invalid token");
				}
			} else {
				return_login_failed ("Token missing");
			}
		} else {
			return_login_failed ("Token missing");
		}
	}
} else {
	return_error ("Method not supported");
}

echo json_encode ($response);
?>

