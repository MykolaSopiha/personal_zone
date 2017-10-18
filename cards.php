<?php
	require 'db.php';

	if (!isset($_SESSION['logged_user'])) {
		header('Location: index.php');
	}

	$data = $_POST;

	// DELETE CARD
	if ( isset($data['deletecard']) ) {
		$deletecard = R::findOne($cards_table, 'number = ?', array($data['deletecard']) );
		R::trash($deletecard);
	}

	// DISABLE CARD
	if ( isset($data['disablecard']) ) {
		$disablecard = R::findOne($cards_table, 'number = ?', array($data['disablecard']) );
		$disablecard->status = 'disable';
		R::store($disablecard);
	}

	// ACTIVATE CARD
	if ( isset($data['activatecard']) ) {
		$activatecard = R::findOne($cards_table, 'number = ?', array($data['activatecard']) );
		$activatecard->status = 'active';
		R::store($activatecard);
	}

	// ADD NEW CARD
	if ( isset($data['go_addcard']) ) {

		//CARD DATA VALIDATION

		$errors = array();

		if ( trim($data['card_name']) == '' ) {
			$errors[] = "Card name is empty!";
		}

		if ( trim($data['card_number']) == '' ) {
			$errors[] = "Card number is empty!";
		}

		if ( trim($data['card_date']) == '' ) {
			$errors[] = "Card date is empty!";
		}

		if (R::count($cards_table, 'name = ?', array($data['card_name'])) > 0) {
			$errors[] = "Card with such name already exists!";
		}

		if (R::count($cards_table, 'number = ?', array($data['card_number'])) > 0) {
			$errors[] = "Card with such number already exists!";
		}

		if ( empty($errors) ) {

			// CARD DATA IS OK! LET'S ADD THIS CARD
			$card = R::dispense($cards_table);
			$card->name     = $data['card_name'];
			$card->number   = $data['card_number'];
			$card->date     = $data['card_date'];
			$card->currency = $data['card_currency'];
			$card->status   = 'active';
			$card->add_date = time();
			R::store($card);

		} else {

			//CARD DATA IS INCORRECT
			// code ... 
			
		}

	}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Карты</title>
	<link rel="icon" type="image/ico" href="favicon.ico">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<style>
		.ui-datepicker-calendar {
			display: none;
		}
	</style>
</head>
<body>

	<?php if ( !empty($errors) ) : ?>
	
		<div class="msg-box msg-box--error">
			<h2>Данные карты некоректны!</h2>
		</div>
	
	<?php elseif ( isset($data['go_addcard']) ) : ?>
		
		<div class="msg-box msg-box--success">
			<h2>Карта добавлена!</h2>
		</div>
	
	<?php endif; ?>

	<div class="msg-box msg-box--success">
		<h2>Привет, <?php echo $_SESSION['logged_user']->login; ?></h2>
		<a class="msg-box__logout" href="index.php">Личный кабинет</a><br>
		<a class="msg-box__index" href="logout.php">Вийти</a>
	</div>

	<div class="full-screen">
		<div class="screen-center">
			<h3 align="center">Добавить карту</h3>
			<form class="menu form-style-1" action="cards.php" method="post">
				<div>
					<label for="card_name">Название карты:</label><br>
					<input id="card_name" type="text" name="card_name" required>
				</div>
				<div>
					<label for="card_number">Код:</label><br>
					<input id="card_number" type="text" name="card_number" pattern="^\d{4}-\d{4}-\d{4}-\d{4}$" placeholder="xxxx-xxxx-xxxx-xxxx" required>
				</div>
				<div>
					<label for="card_date">Дата:</label><br>
					<input id="card_date" type="text" name="card_date" required>
				</div>
				<div>
					<label for="card_currency">Валюта:</label>
					<select name="card_currency" id="card_currency" required>
						<option value="RUB">Рубль</option>
						<option value="USD">Доллар</option>
						<option value="EUR">Евро</option>
						<option value="UAH">Гривна</option>
					</select>
				</div>
				<div>
					<input type="submit" name="go_addcard" value="Добавить">
				</div>
			</form>
		</div>
		<div class="" style="margin-top: 100px;">
			<h3 align="center">Список карт</h3>
			<table id="cards_table" width="100%" cellspacing="2" cellpadding="5">
				<thead>
					<tr>
						<td>#</td>
						<td>Название</td>
						<td>Код</td>
						<td>Валюта</td>
						<td>Дата</td>
						<td>Статус</td>
						<td>Действия</td>
					</tr>
				</thead>
				<tbody>

				<?php

					$card_records = R::findAll($cards_table);
					$counter = 0;
					foreach ($card_records as $record) {
						$counter += 1;
						echo "<tr>";
						echo "<td>$counter</td>";
						echo "<td>$record->name</td>";
						echo "<td>".$record->number."</td>";
						echo "<td>$record->currency</td>";
						echo "<td>$record->date</td>";
						echo "<td>$record->status</td>";
						echo "<td align = 'center'>
								<a class='action' card-number='".$record->number."' href=''>
									<img class='delete_card' src='img/cross.png' title='remove card'>
									<img class='activate_card' src='img/activate.png' title='activate card'>
									<img class='disable_card' src='img/disable.png' title='disable card'>
								</a>
							</td>";
						echo "</tr>";
					}

				?>

				</tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript" src="js/cards.js"></script>
</body>
</html>