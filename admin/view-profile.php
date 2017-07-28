<?php
session_start();

include '../private/functions.php';
require_once '../private/db.php';

if ( !is_logged_in_as_admin() ) {
	if( isset($_GET["username"]) ) {
		$_SESSION['location'] = "admin/view-profile.php?username=".$_GET['username'];
	} else {
		$_SESSION['location'] = "admin/view-profile.php";
	}
	header('location: ../login.php');
	die();
}

$db = new Database();
if( isset($_GET["username"]) ) {
	$profile_name = $_GET["username"];
	$profile = $db->get_user_info($profile_name);
	if( !$profile ) {
		$status = "Profile $profile_name doesn't exist!";
	}
} else {
	$profile_name = $_SESSION['username'];
	$profile = $db->get_user_info($profile_name);
}
$skills = $db->get_user_skills($profile['id']);
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8"> 
	<title><?= htmlspecialchars($profile_name)."'s profile" ?></title>
	<link rel="stylesheet" href="../css/main.css" />
	<link rel="stylesheet" href="../css/mobile.css">
</head>
<body>
<?php include('../private/fragments/fragment-header.php'); ?>
<div id="container">
	<div id="contents">
		<h3><?= isset($status) ? $status : htmlspecialchars($profile_name)."'s profile" ?></h3>
		<div id="profile-info">
			<div><p>Name:</p> <span><?= htmlspecialchars($profile['first_name'])." ".htmlspecialchars($profile['last_name']) ?></span></div>
			<div><p>Phone: <span><?= htmlspecialchars($profile['phone_number']) ?></span></div>
			<div><p>Address: <span><?= htmlspecialchars($profile['address']) ?></span></div>
			<div><span><?= htmlspecialchars($profile['zip_code']) ?> <?= htmlspecialchars($profile['city']) ?></span></div>
			<?php if( strlen(trim($profile['linkedin'])) > 0 ) : ?>
			<div><?= '<a href="'.htmlspecialchars($profile['linkedin']).'">LinkedIn</a>' ?></div>
			<?php endif; ?>
		</div>

	<?php foreach( $skills as $skill ) : ?>
		<p><?= $skill['name'] ?>: <?=$skill['skill_level']?></p>
	<?php endforeach; ?>

		<input type="button" id="fetchVideoBtn" value="Fetch Video" onclick='fetchVideo(<?php echo('"'.$profile_name.'"'); ?>);'>
		<p id="upload-message"> </p>
		<div id="video-container"></div>
		<div id="video-loader"></div>
	</div>
</div>
</body>
<script src="../js/main.js"></script>
</html>
