<form action="login.php" method="post">
	<ul>
		<li>
			<label for="username">Username: </label>
			<input type="text" name="username" id="username" value="<?=isset($username)?$username:''?>" autofocus>
		</li>

		<li>
			<label for="password">Password: </label>
			<input type="password" name="password" id="password">
		</li>

		<li id="login-form-bottom">
			<div>
				<input type="checkbox" name="remember-me" id="remember-me" value="true">
				<label style="display: inline-block" for="remember-me">Remember me</label>
			</div>
			<input type="submit" value="Login" name="loginForm">
		</li>
		<li>
			<br>
			<a href="forgot-password.php">Forgot my password</a>
		</li>
	</ul>

	<?php if ( isset($status) ) : ?>
	<p><?php echo $status; ?></p>
	<?php endif; ?>
</form>
