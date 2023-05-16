<?php
require_once ("includes.php");
require_once ("../../config/config.inc.php");

header ("Content-Type: application/json");

function start_session($name) {
	// Better ways to do this, but works for now.
	global $_DVWA;

	// Create connection
	$conn = new mysqli($_DVWA['db_server'], $_DVWA['db_user'], $_DVWA['db_password'], $_DVWA['db_database'], $_DVWA['db_port']);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$initial_user_data = array ("Name" => "Robin", "Balance" => 100);
						
	// prepare and bind
	// insert into sessions values (null, '{"name": "pippa", "balance": 500}');

	$stmt = $conn->prepare("INSERT INTO sessions (data) VALUES (?)");
	$stmt->bind_param("s", $initial_user_data_json);

	$initial_user_data_json = json_encode($initial_user_data);
	$stmt->execute();

	$id = mysqli_insert_id($conn);

	// echo "New records created successfully, ID: " . $id;

	$stmt->close();
	$conn->close();

	return $id;
}


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
			if ($decoded->Username != "" && $decoded->Password == "Password1") {
				$session_id = start_session(trim($decoded->Username, 10));
				$response["Token"] = "ThisIsYourSecretToken-" . $session_id;
				$response['UserId'] = $session_id;
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

