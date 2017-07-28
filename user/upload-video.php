<?php
/**
 * Adds sent video to database of logged in user if video is smaller than 30 MB
 */
session_start();

include '../private/functions.php';
require_once '../private/db.php';

if ( !is_logged_in() ) {
	http_response_code(401);
	die();
}

#print_r($_FILES["video-blob"]["error"]);

$max_file_size = 30; # MB

if( filesize($_FILES["video-blob"]["tmp_name"]) > $max_file_size*1024*1024) {
	http_response_code(500);
	echo("Video bigger than $max_file_size MB.");
	die();
}

$db = new Database();
$result = $db->update_video( $_FILES["video-blob"]["tmp_name"]);
if($result)
	echo('success');
else
	http_response_code(500);
	echo('Please try again.');

?>
