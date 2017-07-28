<?php
session_start();

include 'private/functions.php';
require_once 'private/db.php';

if ( !is_logged_in() ) {
	$_SESSION['location'] = "view-profile.php";
	header('location: login.php');
	die();
}

$db = new Database();
$profile_name = $_SESSION['username'];
$profile = $db->get_user_info($profile_name);
$skills = $db->get_user_skills($profile['id']);
$all_skills = $db->get_list_of_skills();

foreach($all_skills as $key => $skill) {
	foreach($skills as $s) {
		if($s['id'] === $skill['id']) {
			unset($all_skills[$key]);
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8"> 
	<title><?= htmlspecialchars($profile_name)."'s profile"; ?></title>
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/mobile.css">
</head>
<body>
<?php include('private/fragments/fragment-header.php'); ?>
<div id="container">	
	<div id="contents">
		<h3><?= isset($status) ? $status : htmlspecialchars($profile_name)."'s profile" ?></h3>
		<div id="profile-info">
			<div><p>Name:</p> <span><?= htmlspecialchars($profile['first_name'])." ".htmlspecialchars($profile['last_name']) ?></span></div>
			<div><p>Phone: <span><?= htmlspecialchars($profile['phone_number']) ?></span></div>
			<div><p>Address: <span><?= htmlspecialchars($profile['address']) ?></span></div>
			<div><span><?= htmlspecialchars($profile['zip_code']) ?> <?= htmlspecialchars($profile['city']) ?></span></div>
			<?php if( strlen(trim($profile['linkedin'])) > 0 ) : ?>
			<div><a href="<?= htmlspecialchars($profile['linkedin']) ?>">LinkedIn</a></div>
			<?php endif; ?>
		</div>

		<div id="status">
			<p id="upload-message"> </p>
			<div id="skill-loader"></div>
		</div>
		<button id="fetchVideoBtn" onclick="fetchVideo();">Fetch Video</button>
		<button id="recordBtn" onclick="onBtnRecordClicked()">Record new video</button>
		<button id="stopBtn" onclick="onBtnStopClicked()" disabled>Stop</button>
		<button id="uploadBtn" onclick="upload();" disabled/>Upload</button>

		<div id="video-container"></div>
		<div id="video-loader"></div>

		<aside>
			<select id="skill" name="skill" onchange="addSkill();">
				<option value="">--Select a skill to add--</option>
			<?php foreach($all_skills as $skill): ?>
				<option value="<?=$skill['id'] ?>"><?=$skill['name'] ?></option>
			<?php endforeach; ?>
			</select>
			<input id="search-box" type="text" placeholder="or type to search" oninput="search();"/>
			<div id="search-results"></div>
			<button id="add-missing-skill-btn" disabled onclick="addMissingSkill();">Add missing skill</button>
		</aside>
		<div id="skills">
		<?php foreach( $skills as $skill ): ?>
			<div>
				<label><?=$skill['name']?></label>
				<div>
					<div class="rating fade" id="skill<?=$skill['id']?>" name="skill<?=$skill['id']?>">
					<?php foreach([5,4,3,2,1] as $skill_level):?>
						<?php if($skill_level <= $skill['skill_level']):?>
							<span value="<?=$skill_level?>" skillId="<?=$skill['id']?>" onclick="updateSkill(this)">★</span>
						<?php else: ?>
							<span value="<?=$skill_level?>" skillId="<?=$skill['id']?>" onclick="updateSkill(this)">☆</span>
						<?php endif ?>
					<?php endforeach ?>
					</div>
					<button class="" onclick="deleteSkill(<?=$skill['id'] . ',\'' . $skill['name'] ?>');">X</button><br>
				</div>
			</div>
		<?php endforeach ?>
		</div>
	</div>
</div>

<script src="js/main.js"></script>
<script src="js/skill.js"></script>
<script src="js/camera.js"></script>
</body>
</html>