<?php
session_start();

require_once '../private/db.php';
include '../private/functions.php';

if ( !is_logged_in_as_admin() ) {
	$_SESSION['location'] = "admin/searcher.php";
	header('location: ../login.php');
	die();
}

$db = new Database();
$all_skills = $db->get_list_of_skills();
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8"> 
	<title></title>
	<link rel="stylesheet" href="../css/main.css" />
	<link rel="stylesheet" href="../css/mobile.css">
</head>
<body>
<?php include('../private/fragments/fragment-header.php'); ?>
<div id="container">	
	<div id="contents">
		<div id="status">
			<p id="upload-message"> </p>
			<div id="loader"></div>
		</div>
		<select id="skill" name="skill" onchange="addSkillToSearchFor()">
			<option value="">--Select a skill--</option>
		<?php foreach($all_skills as $skill): ?>
			<option value="<?=$skill['id'] ?>"><?=$skill['name'] ?></option>
		<?php endforeach; ?>
		</select>
		<input id="search-box" type="text" placeholder="or type to search" oninput="search();"/>
		<div id="search-results"></div>

		<div id="skills"></div>
		<div id="results"></div>
	</div>
</div>
<script src="../js/main.js"></script>
<script src="../js/admin.js"></script>
</body>
</html>

