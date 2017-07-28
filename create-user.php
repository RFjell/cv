<?php
session_start();

include 'private/functions.php';
require_once 'private/db.php';

$status = array();
if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirmPassword'];
	$first_name = $_POST['firstname'];
	$last_name = $_POST['lastname'];
	$phone_number = $_POST['phone-number'];
	$address = $_POST['address'];
	$zip_code = $_POST['zip-code'];
	$city = $_POST['city'];
	$linkedin = $_POST['linkedin'];

	if( strlen(trim($username)) === 0 ) {
		$status[] = "You need to supply a user name";
	}
	if( preg_match('/\s/', $username) ) {
		$status[] = "Username mustn't contain spaces";
	}
	if( strlen(trim($first_name)) === 0 ) {
		$status[] = "You need to supply a first name";
	}
	if( strlen(trim($last_name)) === 0 ) {
		$status[] = "You need to supply a last name";
	}
	if( strlen(trim($phone_number)) === 0 ) {
		$status[] = "You need to supply a phone number";
	}
	if( $password !== $confirm_password ) {
		$status[] = "Supplied passwords do not match.";
	} else if( strlen($password) < 8 ) {
		$status[] = "Passwords need to be 8 characters or longer.";
	}
	if( count($status) === 0 ) {
		$db = new Database();
		$result = $db->add_user($username, $password, $first_name, $last_name, $phone_number, $address, $zip_code, $city, $linkedin);
		if( $result ) {
			$_SESSION['username'] = $username;
			$_SESSION['last_activity'] = time();
			$_SESSION['admin'] = false;
			send_account_created_mail($username);
			header('location: view-profile.php');
		} else {
			$status[] = "Username already exists";
		}
	}
}
?>

<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8"> 
	<title>Create user page</title>
	<link rel="stylesheet" href="css/login.css" />
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/mobile.css" />
</head>
<body>
<?php include('private/fragments/fragment-header.php'); ?>
<div id="container">
	<h1>User information</h1>
	<form action="create-user.php" method="post">
		<ul>
			<li>
				<label for="username">Email / username: </label>
				<input type="email" name="username" value="<?=isset($username)?$username:''?>" required autofocus>
			</li>
			<?php include('private/fragments/fragment-user-form.php'); ?>
			<li>
				<label for="password">Password: </label>
				<input type="password" name="password" required>
			</li>

			<li>
				<label for="confirmPassword">Confirm password: </label>
				<input type="password" name="confirmPassword" required>
			</li>

			<li>
				<input type="submit" value="Create user" name="createUserForm">
			</li>
		</ul>

		<?php foreach($status as $message) : ?>
		<p><?= $message; ?></p>
		<?php endforeach; ?>
	</form>
</div>
<script src="js/main.js"></script>
</body>
</html>

