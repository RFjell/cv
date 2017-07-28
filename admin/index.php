<?php
session_start();

require_once '../private/db.php';
include '../private/functions.php';

if ( !is_logged_in_as_admin() ) {
	$_SESSION['location'] = "admin/index.php";
	header('location: ../login.php');
	die();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8"> 
	<title></title>
	<link rel="stylesheet" href="../css/main.css" />
	<link rel="stylesheet" href="../css/mobile.css" />
</head>
<body>
<?php include('../private/fragments/fragment-header.php'); ?>
<div id="container">	
	<div id="contents">
	</div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
