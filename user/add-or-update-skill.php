<?php
/**
 * Takes skill-id and skill-level and adds to logged in users' db
 */
session_start();

include '../private/functions.php';
require_once '../private/db.php';

if ( !is_logged_in() ) {
	http_response_code(401);
	die();
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$skill = $_POST['skill'];
	$skill_level = $_POST['skill-level'];

	$username = $_SESSION['username'];
	$db = new Database();
	$result = $db->add_or_update_skill($username, $skill, $skill_level);
	if($result) {
		$status = "Skill added";
	} else {
		http_response_code(500);
		$status = "Error adding skill. Try again.";
	}
}

echo($status);
?>


