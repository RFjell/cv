<?php
include 'private/functions.php';
require_once 'private/db.php';

$status = array();
if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$username = $_POST['username'];

	$db = new Database();
	$hash = $db->forgot_password($username);

	if($hash) {
		$db->send_forgotten_password_mail($username, $hash);
		$status[] = "Check your email for code to reset your password";
	} else {
		$status[] = "Email not in database";
	}
} else if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['hash']) ) {
	$db = new Database();
	$username = $db->get_username_from_hash($_GET['hash']);

	if($username) {
		$db->reset_forgot_password_hash($username);
		$new_password = $db->create_new_random_password_for($username);
		if( $new_password )
			$status[] = "Your new password is: " . $new_password.". Don't forget to change it!";
		else
			$status[] = "Error creating new password. Please try again.";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8"> 
	<title>Recover password</title>
	<link rel="stylesheet" href="css/login.css" />
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/mobile.css">
</head>
<body>
<?php include('private/fragments/fragment-header.php'); ?>
<div id="container">	
	<div id="contents">
		<form action="forgot-password.php" method="post">
			<ul>
				<li>
					<h3>Enter your email</h3>
				</li>
				<li>
					<label for="username">Email / username: </label>
					<input type="text" name="username" required autofocus>
				</li>
			</ul>
		</form>
		<?php foreach($status as $message) : ?>
		<p><?= $message; ?></p>
		<?php endforeach; ?>
		<form action="forgot-password.php" method="get">
			<ul>
				<li>
					<h3>Or enter received code to continue</h3>
				</li>
				<li>
					<label for="hash">Code: </label>
					<input type="text" name="hash" required>
				</li>
			</ul>
		</form>
	</div>
</div>

<script src="js/main.js"></script>
</body>
</html>
