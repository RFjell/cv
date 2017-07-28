<?php
/**
 * Takes a skill id and removes that skill from logged in user
 */
session_start();

include '../private/functions.php';
require_once '../private/db.php';

if ( !is_logged_in() ) {
	http_response_code(401);
	die();
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$skill_id = $_POST['skill-id'];

	$db = new Database();
	$result = $db->remove_user_skill($skill_id);
	if($result) {
		$status = "Skill removed.";
	} else {
		http_response_code(500);
		$status = "Error removing skill.";
	}
}

echo($status);
?>


