<?php
session_start();

include 'private/functions.php';
require_once 'private/db.php';

if ( !is_logged_in() ) {
	$_SESSION['location'] = "update-user.php";
	header('location: login.php');
	die();
}

$status = array();
$db = new Database();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$first_name = $_POST['firstname'];
	$last_name = $_POST['lastname'];
	$phone_number = $_POST['phone-number'];
	$address = $_POST['address'];
	$zip_code = $_POST['zip-code'];
	$city = $_POST['city'];
	$linkedin = $_POST['linkedin'];
	$current_password = $_POST['current-password'];
	$new_password = $_POST['new-password'];
	$confirm_new_password = $_POST['confirm-new-password'];

	if( check_user_creds( $_SESSION['username'], $current_password ) ) {
		if( $new_password !== '') {
			if( $new_password !== $confirm_new_password ) {
				$status[] = "New passwords do not match";
			} else if( strlen(trim($new_password)) < 8) {
				$status[] = "Password must be 8 characters or longer";
			} else {
				$res = $db->update_password( $_SESSION['username'], $new_password );
				if( $res ) {
					$status[] = "Password successfully updated";
				} else {
					$status[] = "Error updating password";
				}
			}
		}
	} else {
		$status[] = "Wrong password";
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
	if( count($status) === 0 ) {
		$result = $db->update_user($_SESSION['username'], $first_name, $last_name, $phone_number, $address, $zip_code, $city, $linkedin);
		if($result) {
			$status[] = "User info updated!";
		} else {
			$status[] = "Problem updating user info";
		}
	}
} else {
	$profile = $db->get_user_info($_SESSION['username']);
	$first_name = $profile['first_name'];
	$last_name = $profile['last_name'];
	$phone_number = $profile['phone_number'];
	$address = $profile['address'];
	$zip_code = $profile['zip_code'];
	$city = $profile['city'];
	$linkedin = $profile['linkedin'];
}
?>

<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8"> 
	<title>Update user info page</title>
	<link rel="stylesheet" href="css/login.css" />
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/mobile.css" />
</head>
<body>
<?php include('private/fragments/fragment-header.php'); ?>
<div id="container">
	<div id="contents">
		<h1>Update User Information</h1>
		<form action="update-user.php" method="post">
			<ul>
				<li>
					<label for="current-password">Current Password: </label>
					<input type="password" name="current-password" id="current-password" value="" required autofocus>
				</li>
				<?php include('private/fragments/fragment-user-form.php'); ?>
				<li>
					<label for="new-password">New Password: </label>
					<input type="password" name="new-password" id="new-password" value="">
				</li>
				<li>
					<label for="confirm-new-password">Confirm New Password: </label>
					<input type="password" name="confirm-new-password" id="confirm-new-password" value="">
				</li>
				<li>
					<input type="submit" value="Update user info" name="updateUserForm">
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

