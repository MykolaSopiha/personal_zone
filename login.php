<?php

	require 'db.php';

	$data = $_POST;

	if (isset($data['do_signin'])) {

		$errors = array();
		$user = R::findOne($users_table, 'login = ?', array($data['user_login']));
		
		if ($user) {
			
			// LOGIN EXSISTS
			if (password_verify($data["user_password"], $user->password)) {
				// EVERYTHING IS OK
				$_SESSION['logged_user'] = $user;
				echo "<div class='msg-box msg-box--success'>";
				echo "<h2>Login and password are correct</h2>";
				echo "<h2><a href='index.php'>Private Zone</a></h2>";
				echo "</div>";
			} else {
				$errors[] = "Password is incorrect!";
			}

		} else {
			$errors[] = "User with this login not founded!";
		}

		if ( !empty($errors) ) {
			echo "<div class='error_msg'>";
			echo "<h2>".$errors[0]."</h2>";
			echo "</div>";
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link rel="icon" type="image/ico" href="favicon.ico">
	<link rel="stylesheet" href="css/style.css">
	<style>
		html, body {
			height: 100%;
		}
		.container {
			height: 100%;
			display: flex;
		}
		form {
			margin: auto;
			text-align: center;
		}
		form div {
			margin: 20px 0;
		}
		.error_msg, .success_msg {
			position: absolute;
			top: 10px;
			left: 10px;
			padding: 10px;
		}
		.error_msg {
			background-color: red;
			color: black;
		}
		.success_msg {
			background-color: green;
			color: white;
		}
	</style>
</head>
<body>
	<div class="container">
		<form action="login.php" method="post">
			<h1>Login</h1>
			<div>
				<label for="user_login">Login:</label><br>
				<input id="user_login" name="user_login" type="text" value="">
			</div>
			<div>
				<label for="user_password">Password:</label><br>
				<input id="user_password" name="user_password" type="password">
			</div>
			<div>
				<input type="submit" value="Sign in!" name="do_signin">
			</div>
		</form>
	</div>
</body>
</html>