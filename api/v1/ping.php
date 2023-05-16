<?php
header ("Content-Type: application/json");
$response = array ("Ping" => "Pong");
echo json_encode ($response);
?>
