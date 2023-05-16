<?php
require_once ("../../config/config.inc.php");

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

function db_connect() {
	// Better ways to do this, but works for now.
	global $_DVWA;
	global $conn;

	if (!is_null ($conn)) {
		return $conn;
	}

	// Create connection
	$conn = new mysqli($_DVWA['db_server'], $_DVWA['db_user'], $_DVWA['db_password'], $_DVWA['db_database'], $_DVWA['db_port']);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	return $conn;
}

function update_profile($user_id, $name) {
	$conn = db_connect();

	$data = get_data($user_id);
	// var_dump ($data);
	$data->Name = $name;

	$stmt = $conn->prepare("UPDATE sessions SET data = ? WHERE session_id = ?");
	// print "The ID is " . $user_id . "\n";
	$stmt->bind_param("si", json_encode($data), $user_id);
	$stmt->execute();

	$result = $stmt->get_result();
	return true;
}

function update_balance($user_id, $change) {
	$conn = db_connect();

	$data = get_data($user_id);
	// var_dump ($data);
	$data->Balance = $data->Balance + $change;

	$stmt = $conn->prepare("UPDATE sessions SET data = ? WHERE session_id = ?");
	// print "The ID is " . $user_id . "\n";
	$stmt->bind_param("si", json_encode($data), $user_id);
	$stmt->execute();

	$result = $stmt->get_result();
	return true;
}

function get_balance($user_id) {
	// Better ways to do this, but works for now.
	global $_DVWA;

	// Create connection
	$conn = new mysqli($_DVWA['db_server'], $_DVWA['db_user'], $_DVWA['db_password'], $_DVWA['db_database'], $_DVWA['db_port']);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$stmt = $conn->prepare("SELECT data FROM sessions WHERE session_id = ?");
	// print "The ID is " . $user_id . "\n";
	$stmt->bind_param("i", $user_id);
	$stmt->execute();

	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	
	// var_dump ($row);
	$user_data = $row['data'];
	$user_data_json = json_decode($user_data);
	// var_dump ($user_data_json);
	$balance = $user_data_json->Balance;
	// var_dump ($user_id);
	// var_dump ($balance);
	return $balance;
}

function check_login() {
	if (!array_key_exists ("HTTP_AUTHORIZATION", $_SERVER)) {
		return_unauthorised ("No Authorization header supplied");
	} else {
		$header = $_SERVER["HTTP_AUTHORIZATION"];
		if (preg_match ("/^bearer (.*)$/i", $header, $matches)) {
			if (count($matches) == 2) {
				$token = $matches[1];

				if (preg_match("/ThisIsYourSecretToken-([0-9]*)$/", $token, $matches)) {
					if (count ($matches) == 2) {
						$id = intval ($matches[1]);

						if ($id > 0) {
							// Better ways to do this, but works for now.
							global $_DVWA;

							// Create connection
							$conn = new mysqli($_DVWA['db_server'], $_DVWA['db_user'], $_DVWA['db_password'], $_DVWA['db_database'], $_DVWA['db_port']);

							// Check connection
							if ($conn->connect_error) {
								die("Connection failed: " . $conn->connect_error);
							}
							$stmt = $conn->prepare("SELECT data FROM sessions WHERE session_id = ?");
							// print "The ID is " . $id . "\n";
							$stmt->bind_param("i", $id);
							$stmt->execute();

							$result = $stmt->get_result();
							$row = $result->fetch_assoc();
							
							// var_dump ($row);
							$user_data = $row['data'];
							$user_data_json = json_decode($user_data);
							// var_dump ($user_data);
							// var_dump ($user_data_json);
						} else {
							return_unauthorised ("Invalid token");
						}
					} else {
						return_unauthorised ("Invalid token");
					}
				} else {
					return_unauthorised ("Invalid token");
				}
			} else {
				return_unauthorised ("Token missing");
			}
		} else {
			return_unauthorised ("Token missing");
		}
	}
	return $user_data_json;
}

function get_data($user_id) {
	global $conn;

	$conn = db_connect();

	if ($user_id > 0) {
		// Better ways to do this, but works for now.
		global $_DVWA;

		// Create connection
		$conn = db_connect();
		$stmt = $conn->prepare("SELECT data FROM sessions WHERE session_id = ?");
		//print "The ID is " . $user_id . "\n";
		$stmt->bind_param("i", $user_id);
		$stmt->execute();

		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		
		// var_dump ($row);
		$user_data = $row['data'];
		$user_data_json = json_decode($user_data);
		// var_dump ($user_data);
		// var_dump ($user_data_json);
	} else {
		return_unauthorised ("Invalid user");
	}
	return $user_data_json;
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
