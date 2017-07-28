<?php
/**
 * Deletes a skill from the database. Only an admin can do it
 */
session_start();

include '../private/functions.php';
require_once '../private/db.php';

if ( !is_logged_in_as_admin() ) {
	http_response_code(403);
	die();
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$skill_id = $_POST['skill-id'];
	$db = new Database();

	$result = $db->remove_skill($skill_id);
}

if( $result ) {
	echo("success");
} else {
	http_response_code(500);
	echo("Error deleting skill. Try again.");
}
?>

