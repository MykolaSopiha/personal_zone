<?php
	require 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Champion Zone</title>
	<link rel="icon" type="image/ico" href="favicon.ico">
	<link rel="stylesheet" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
<body>

<?php if ( !isset($_SESSION['logged_user']) ) : ?>

	<div class="full-screen">
		<div class="menu">
			<h2><a href="login.php">Login</a></h2>
			<h2><a href="reg.php">Registration</a></h2>
		</div>
	</div>

<?php else : ?>

		<div class="msg-box msg-box--success">
			<h2>Привет, <?php echo $_SESSION['logged_user']->login; ?></h2>
			<a class="msg-box__index" href="index.php">Личный кабинет</a><br>
			<a class="msg-box__logout" href="logout.php">Выйти</a>
		</div>
		
	<div class="full-screen">

		<div class="screen-center">
			<div class="menu">
				<h3>Меню</h3>
				<ul class="menu">
					<li><a class="" href="statistics.php">Статистика</a></li>
					<li><a href="cards.php">Карты</a></li>
					<li><a class="menu__disable" href="#">Баланс</a></li>
					<li><a class="menu__disable" href="#">Мотивационная система</a></li>
					<li><a href="data.php">Данные</a></li>
					<li><a class="menu__disable" href="#">Wiki</a></li>
				</ul>
			</div>
		</div>

	</div>

<?php endif; ?>

</body>
</html>