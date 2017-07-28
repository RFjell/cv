<?php
session_start();

include 'private/functions.php';
require_once 'private/db.php';

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	if( check_user_creds($username, $password) ) {
		$_SESSION['username'] = $username;
		$_SESSION['last_activity'] = time();
		if( isset( $_POST['remember-me'] ) ) {
			$_SESSION['remember-me'] = true;
		}
		if(isset($_SESSION['location'])) {
			$location = $_SESSION['location'];
			unset($_SESSION['location']);
			header("Location: ".$location);
		} else {
			if(is_logged_in_as_admin())
				header("Location: admin/index.php");
			else
				header("Location: view-profile.php");
		}
	} else {
		$status = "Incorrect login credentials.";
	}
}
?>

<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8"> 
	<title>Login page</title>
	<link rel="stylesheet" href="css/login.css" />
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/mobile.css">
</head>
<body>
<?php include('private/fragments/fragment-header.php'); ?>

<div id="container">
<h1>Login</h1>
<?php include('private/fragments/fragment-login-form.php'); ?>
</div>
<script src="js/main.js"></script>
</body>
</html>

