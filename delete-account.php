<?php
session_start();

include 'private/functions.php';
require_once 'private/db.php';

if ( !is_logged_in() ) {
	$_SESSION['location'] = "delete-account.php";
	header('location: login.php');
	die();
}

$status = array();
if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$current_password = $_POST['current-password'];
	if( !check_user_creds($_SESSION['username'], $current_password) ) {
		$status[] = "Wrong password";
	} else {
		$db = new Database();
		$res = $db->delete_account( $_SESSION['username'] );
		if( $res )
			header('Location: logout.php');
		else
			$status[] = "Error deleting account";
	}
}
?>

<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8"> 
	<title>Delete account</title>
	<link rel="stylesheet" href="css/login.css" />
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/mobile.css" />
</head>
<body>
<?php include('private/fragments/fragment-header.php'); ?>
<div id="container">
	<div id="contents">
		<h1>Delete Account</h1>
		<form action="delete-account.php" method="post" style="margin:auto">
			<ul>
				<li>
					<label for="current-password">Current Password: </label>
					<input type="password" name="current-password" id="current-password" value="" required autofocus>
				</li>
				<li>
					<input type="submit" value="Delete Account" name="deleteAccountForm">
				</li>
			</ul>
			<?php foreach($status as $message) : ?>
			<p><?= $message; ?></p>
			<?php endforeach; ?>
		</form>
	</div>
</div>
<script src="js/main.js"></script>
</body>
</html>

