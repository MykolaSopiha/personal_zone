<?php

	require 'db.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Registration</title>
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

<?php

	$data = $_POST;

	if (isset($data["do_signup"])) {

		// REGISTRATION OF NEW USER
		$errors = array();

		if (trim($data["user_login"]) == '') {
			$errors[] = "\"Login\" is empty!";
		}

		if( preg_match("/^[a-zA-Z0-9]+$/", trim($data['user_login']) ) != 1) {
			$errors[] = "\"Login\" isn't correct!<br>(a-z and 0-9 only!)";
		}

		if (trim($data["user_email"]) == '') {
			$errors[] = "\"E-mail\" is empty!";
		}

		if ($data["user_password"] == '') {
			$errors[] = "\"Password\" is empty!";
		}

		if ($data["user_password"] != $data["user_password2"]) {
			$errors[] = "Passwords are not equal!";
		}

		if ($data["terra_id"] == '') {
			$errors[] = "\"Terra Leads ID\" is empty!";
		}

		if (R::count($users_table, 'login = ?', array($data['user_login'])) > 0) {
			$errors[] = "User with such login already exists!";
		}

		if (R::count($users_table, 'email = ?', array($data['user_email'])) > 0) {
			$errors[] = "User with such email already exists!";
		}

		if (R::count($users_table, 'terra_id = ?', array($data['terra_id'])) > 0) {
			$errors[] = "User with such Terra Leads ID already exists!";
		}

		if (!empty($errors)) {
			
			// FORM IS NOT CORRECT
			echo "<div class='error_msg'>";
			echo "<h2>FORM IS NOT CORRECT</h2>";
			echo "<h3>".$errors[0]."</h3>";
			echo "</div>";

		} else {

			// ALL RIGHT! LET'S REGISTRATE
			$user = R::dispense($users_table);
			$user->login     = $data['user_login'];
			$user->password  = password_hash($data['user_password'], PASSWORD_DEFAULT);
			$user->email     = $data['user_email'];
			$user->terra_id  = $data['terra_id'];
			$user->join_date = time();
			R::store($user);

			echo "<div class='success_msg'>";
			echo "<h2>You have successfully registered!</h2>";
			echo "<h2><a href='login.php'>Now you can login!</a></h2>";
			echo "</div>";
		}
		
	} else {
	
		$data["user_login"] = "";
		$data["user_email"] = "";
		$data["terra_id"] = "";

	}
?>

		<form action="reg.php" method="post">
			<h1>Registration</h1>
			<div>
				<label for="user_login">Login:</label><br>
				<input id="user_login" name="user_login" type="text" value="<?=$data["user_login"]?>">
			</div>
			<div>
				<label for="user_email">E-mail:</label><br>
				<input id="user_email" name="user_email" type="email" value="<?=$data["user_email"]?>">
			</div>
			<div>
				<label for="user_password">Password:</label><br>
				<input id="user_password" name="user_password" type="password">
			</div>
			<div>
				<label for="user_password2">Password again:</label><br>
				<input id="user_password2" name="user_password2" type="password">
			</div>
			<div>
				<label for="terra_id">Terra Leads ID:</label><br>
				<input id="terra_id" name="terra_id" type="text" value="<?=$data["terra_id"]?>">
			</div>
			<div>
				<input type="submit" value="Sign up!" name="do_signup">
			</div>
			<div>
				<a href="index.php">Go back</a>
			</div>
		</form>
	</div>
</body>
</html>