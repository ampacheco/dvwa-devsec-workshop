<?php
header ("Content-Type: application/json");

$response = array ("Query" => $_GET);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ($_SERVER['CONTENT_TYPE'] == "application/json") {
		$body = file_get_contents('php://input');
		$decoded = json_decode($body);
		if (is_null ($decoded)) {
			$response["Body"] = "Error - Invalid JSON submitted";
		} else {
			$response["Body"] = $decoded;
		}
	} else {
		$response["Body"] = "Error - Content type not JSON";
	}
}

echo json_encode ($response);
?>
