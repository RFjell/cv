<?php
/**
 * Fetches the video of logged in user or any given user if logged in as admin
 */
session_start();

include '../private/functions.php';
require_once '../private/db.php';

if ( !is_logged_in() ) {
	http_response_code(401);
	die();
}

if( isset($_GET["username"]) and is_logged_in_as_admin() ) {
	$profile_name = $_GET["username"];
} else {
	$profile_name = $_SESSION['username'];
}

$db = new Database();
$video = $db->get_video($profile_name);
if( $video ) {
	echo('data:video/webm;base64,' . base64_encode($video));
} else {
		http_response_code(500);
}

?>
