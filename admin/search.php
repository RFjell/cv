<?php
/**
 * Takes a list of skill ids and skill levels and returns a list of user who qualify
 */
session_start();

require_once '../private/db.php';
include '../private/functions.php';

if ( !is_logged_in_as_admin() ) {
	http_response_code(403);
	die();
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$list = json_decode($_POST['list']);
}

$db = new Database();
foreach($db->search($list) as $result)
	echo($result[0]."\n");
?>

