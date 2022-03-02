<?php
print "Query string parameters:\n\n";
var_dump ($_GET);

if ($_SERVER['CONTENT_TYPE'] == "application/json") {
	$body = file_get_contents('php://input');
	print "The JSON body submitted is:\n\n";
	var_dump ($body);
}

?>
