<?php

	$hosting  = "localhost";
	$login    = "root";
	$db_name  = "championZone";
	$password = "";

	require('libs/rb.php');
	R::setup( 'mysql:host='.$hosting.';dbname='.$db_name, $login, $password );

	session_start();

	if (isset($_SESSION['logged_user']->login)) {
		$cards_table = $_SESSION['logged_user']->login.'cards';
		$costs_table = $_SESSION['logged_user']->login.'costs';
	}

	$users_table = 'users';