<?php

/**
 * Returns true if user is logged in and active
 */
function is_logged_in()
{
	if( !isset($_SESSION['username'])){
		return false;
	}

	if( isset($_SESSION['remember-me'])){
		return true;
	}

	// Check if user has done anything in the last 10 minutes
	$now = time();
	if( $now - $_SESSION['last_activity'] > 60*10 ) {
		return false;
	}

	$_SESSION['last_activity'] = time();
	return true;
}

/**
 * Check if current user is an admin
 */
function is_logged_in_as_admin()
{
	if( isset($_SESSION['admin'])) {
		return $_SESSION['admin'];
	}
	$db = new Database();
	if( isset($_SESSION['username']) ) {
		$_SESSION['admin'] = ($db->get_user_role($_SESSION['username']) === 'admin');
		return $_SESSION['admin'];
	} else {
		return false;
	}
}

/**
 * Check if password matches the one stored in the database
 */
function check_user_creds($username, $password)
{
	$db = new Database();
	$hash = $db->get_password( $username);
	if( !$hash )
		return false;
	return password_verify($password, $hash);
}

/**
 * Send mail with code to reset password
 */
function send_forgotten_password_mail($username, $hash)
{
	#$body = 'Please click the following link to reset your password: <a href="http://peoplefirst.se/cv/forgot-password.php?hash='.$hash.'">Link</a>';
	#mail($username, "Reset password", $body);
	$body = "Use the following code to reset your password:\r\n";
	$body .= $hash;

	#$headers[] = 'MIME-Version: 1.0';
	#$headers[] = 'Content-type: text/html; charset=iso-8859-1';
	#$headers[] = 'From: ATM193351 <193351@binero.net>';

	#mail($username, "Reset password", $body, implode("\r\n",$headers), "-f <193351@binero.net>");
	mail($username, "Reset password", $body);
}

/**
 * Send a welcome mail
 */
function send_account_created_mail($username)
{
	$body = 'Welcome!';
	mail($username, "Account created", $body);
}
