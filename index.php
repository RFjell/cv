<?php
session_start();

include 'private/functions.php';
require_once 'private/db.php';

?>
<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8"> 
	<title>RFjell's cv thingy</title>
	<link rel="stylesheet" href="css/login.css" />
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/mobile.css">
</head>

<body>
<?php include('private/fragments/fragment-header.php'); ?>
<div id="container">
	<div id="login-div">
		<h1>Login</h1>
		<?php include('private/fragments/fragment-login-form.php'); ?>
	</div>
	<div id="create-user-div">
		<h1>Create Account</h1>
		<form action="create-user.php" method="post">
			<ul>
				<li>
					<label for="username">Email / username: </label>
					<input type="email" name="username" id="username" value="<?=(isset($username))?$username:''?>" required>
				</li>
				<?php include('private/fragments/fragment-user-form.php'); ?>
				<li>
					<label for="password">Password: </label>
					<input type="password" name="password" id="password" required>
				</li>

				<li>
					<label for="confirmPassword">Confirm password: </label>
					<input type="password" name="confirmPassword" id="confirmPassword" required>
				</li>

				<li>
					<input type="submit" value="Create user" name="createUserForm">
				</li>
			</ul>
		</form>
	</div>
</div>
<script src="js/main.js"></script>
</body>
</html>
