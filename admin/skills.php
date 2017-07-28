<?php
session_start();

include '../private/functions.php';
require_once '../private/db.php';

if ( !is_logged_in_as_admin() ) {
	$_SESSION['location'] = "admin/skills.php";
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
		<h1>Add or remove skills</h1>
		<div id="status">
			<p id="upload-message"> </p>
			<div id="loader"></div>
		</div>
		<select id="skill" name="skill">
			<option value="">--Select a skill--</option>
		<?php foreach($all_skills as $skill): ?>
			<option value="<?=$skill['id'] ?>"><?=$skill['name'] ?></option>
		<?php endforeach; ?>
		</select>
		<button onclick="deleteSkill();">Delete selected skill</button><br/>
		<label for="newSkill">Name of new skill:</label>
		<input type="text" id="newSkill" name="newSkill">
		<input type="button" value="Add skill" id="addSkillBtn" onclick="addSkill();"><br>
	</div>
</div>
<script src="../js/main.js"></script>
<script src="../js/admin.js"></script>
</body>
</html>
