<?php

$users = array (
				101 => array ("name" => "Robin", "balance" => 100),
				102 => array ("name" => "Pippa", "balance" => 66),
				105 => array ("name" => "Sam", "balance" => 31337),
			);

function return_unauthorised($message) {
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

function check_login() {
	if (!array_key_exists ("HTTP_AUTHORIZATION", $_SERVER)) {
		return_unauthorised ("No Authorization header supplied");
	} else {
		$header = $_SERVER["HTTP_AUTHORIZATION"];
		if (preg_match ("/^bearer (.*)$/i", $header, $matches)) {
			if (count($matches) == 2) {
				$token = $matches[1];
				if ($token != "ThisIsYourSecretToken") {
					return_unauthorised ("Invalid token");
				}
			} else {
				return_unauthorised ("Token missing");
			}
		} else {
			return_unauthorised ("Token missing");
		}
	}
	return true;
}

function get_json_body() {
	if ($_SERVER['CONTENT_TYPE'] == "application/json") {
		$body = file_get_contents('php://input');
		$decoded = json_decode($body);

		if (is_null ($decoded)) {
			return_error ("Invalid JSON submitted");
		}
	} else {
		return_error ("Content type not JSON");
	}

	return $decoded;
}

?>
