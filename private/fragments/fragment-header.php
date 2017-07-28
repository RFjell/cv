<header id="header">
	<ul>
		<li style="float:right;" class="icon"><a href="javascript:void(0);" onclick="toggleHeaderMenu()">&#9776;</a></li>
		<li class="always-show"><a href="http://peoplefirst.se" class="no-padding"><img src="http://peoplefirst.se/wp-content/uploads/2017/03/logga-pf.jpg"></a></li>
		<?php if( is_logged_in_as_admin() ): ?>
		<li><a href="searcher.php">Search</a></li>
		<li><a href="skills.php">Skills</a></li>
		<li class="right-floating"><a href="../logout.php">Logout</a></li>
		<?php elseif( is_logged_in() ): ?>
		<li><a href="view-profile.php">View Profile</a></li>
		<li><a href="update-user.php">Update User Info</a></li>
		<li><a href="delete-account.php">Delete Account</a></li>
		<li class="right-floating"><a href="logout.php">Logout</a></li>
		<?php else: ?>
		<li class="right-floating"><a href="create-user.php">Create Account</a></li>
		<li class="right-floating"><a href="login.php">Login</a></li>
		<?php endif ?>
	</ul>
</header>
