<?php
/**
 * Adds a new skill to the database
 */
session_start();

include '../private/functions.php';
require_once '../private/db.php';

#if ( !is_logged_in_as_admin() ) {
if ( !is_logged_in() ) {
	http_response_code(401);
	die();
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$skill_name = $_POST['skill-name'];
	$db = new Database();

	$result = $db->add_skill($skill_name);
}

if($result) {
	echo($result);
} else {
	http_response_code(500);
	echo("Error adding skill.");
}

?>

