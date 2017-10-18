<?php

	require 'db.php';

	if (!isset($_SESSION['logged_user'])) {
		header('Location: index.php');
	}

	$data = $_POST;

	// DELETE COST
	if ( isset($data['deletecost']) ) {
		$deletecost = R::findOne($costs_table, 'id = ?', array($data['deletecost']) );
		R::trash($deletecost);
	}

	if ( isset($data['go_addcosts']) ) {

		$errors = array();

		$format = '%d-%m-%Y';

		$masks = array( 
			'%d' => '(?P<d>[0-9]{2})', 
			'%m' => '(?P<m>[0-9]{2})', 
			'%Y' => '(?P<Y>[0-9]{4})', 
			'%H' => '(?P<H>[0-9]{2})', 
			'%M' => '(?P<M>[0-9]{2})', 
			'%S' => '(?P<S>[0-9]{2})', 
		);

		$rexep = "#".strtr(preg_quote($format), $masks)."#";
		
		if( !preg_match($rexep, $data['date'], $out) ) {
			$errors[] = 'Date is not correct!';
		}

		if (R::count($cards_table, 'number = ?', array($data['card'])) == 0) {
			$errors[] = "Card doesn't exist!";
		}

		if (!is_numeric($data['spent'])) {
			$errors[] = "Spent money value is not correct!";
		}

		if (!is_numeric($data['currency_rate'])) {
			$errors[] = "Currency value is not correct!";
		}

		if ( $data['currency'] != 'USD' && $data['currency'] != 'EUR' && $data['currency'] != 'UAH' && $data['currency'] != 'RUB' ) {
			$errors[] = "Currency is not correct!";
		}

		if ( !empty($errors) ) {

			//HOUSTON, WE HAVE A PROBLEM
			print_r($errors);

		} else {

			// EVERYTHING IS OK. WE CAN STORE DATA
			$date_str = $out['d']."-".$out['m']."-".$out['Y'];
			$spent    = (float) $data['spent'];
			$currency_rate = (float) $data['currency_rate'];
			
			$cost = R::dispense($costs_table);
			$cost->date             = $date_str;
			$cost->card_number      = $data['card'];
			$cost->value            = $spent;
			$cost->currency         = $data['currency'];
			$cost->currency_rate    = $currency_rate;
			$cost->record_time      = time();
			R::store($cost);
		}
	}

	unset($_POST);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Данные</title>
	<link rel="icon" type="image/ico" href="favicon.ico">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
</head>
<body>

	<div class="msg-box msg-box--success">
		<h2>Привет, <?php echo $_SESSION['logged_user']->login; ?></h2>
		<a class="msg-box__logout" href="index.php">Личный кабинет</a><br>
		<a class="msg-box__index" href="logout.php">Вийти</a>
	</div>

	<div class="full-screen">
		<div class="screen-center">
			<h3>Добавить данные</h3>
			<form class="menu form-style-1" action="data.php" method="post">
				<div>
					<label for="date_picker">Дата:</label><br>
					<input id="date_picker" type="text" name="date" required>
				</div>
				<div>
					<label for="card">Карта:</label><br>
					<select name="card" id="card">

						<?php
							$cards = R::findAll($cards_table);
							foreach ($cards as $card) {
								if ($card->status == 'disable') continue;
								$number_str = substr($card->number, -4);
								echo "<option value=".$card->number.">$card->name - *$number_str</option>";
							}
						?>

					</select>
				</div>
				<div>
					<label for="spent">Потрачено:</label><br>
					<input id="spent" type="number" min="0" step="0.01" name="spent" required>
				</div>
				<div>
					<label for="currency">Валюта:</label>
					<select name="currency" id="currency">
						<option value="RUB">Рубль</option>
						<option value="USD">Доллар</option>
						<option value="EUR">Евро</option>
						<option value="UAH">Гривна</option>
					</select>
				</div>
				<div>
					<label for="currency_rate">Курс относительно USD:</label><br>
					<input id="currency_rate" type="number" min="0" step="0.0001" name="currency_rate" required>
				</div>
				<div>
					<input type="submit" name="go_addcosts" value="Добавить">
				</div>
			</form>
		</div>

		<div class="screen-center" style="margin-top: 100px;">
			<table id="costs_table" width="100%" cellspacing="2" cellpadding="5">
				<thead>
					<tr>
						<td>#</td>
						<td>Дата</td>
						<td>Номер карты</td>
						<td>Объем</td>
						<td>Валюта</td>
						<td>Курс относительно USD</td>
						<td>Удалить</td>
					</tr>
				</thead>
				<tbody>

				<?php
					$cost_records = R::findAll($costs_table);
					$counter = 0;
					foreach ($cost_records as $record) {
						$counter += 1;
						echo "<tr>";
						echo "<td>$counter</td>";
						echo "<td>$record->date</td>";
						echo "<td>$record->card_number</td>";
						echo "<td>$record->value</td>";
						echo "<td>$record->currency</td>";
						echo "<td>$record->currency_rate</td>";
						echo "<td align = 'center'>
							<a class='action' cost-id='".$record->id."' href=''>
								<img class='delete_card' src='img/cross.png' title='remove record'>
							</a>
						</td>";
						echo "</tr>";
					}

				?>

				</tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript" src="js/datepicker-ru.js"></script>
	<script type="text/javascript" src="js/data.js"></script>
	<script type="text/javascript">
		function getRate(from, to) {
			var script = document.createElement('script');
			script.setAttribute('src', "http://query.yahooapis.com/v1/public/yql?q=select%20rate%2Cname%20from%20csv%20where%20url%3D'http%3A%2F%2Fdownload.finance.yahoo.com%2Fd%2Fquotes%3Fs%3D"+from+to+"%253DX%26f%3Dl1n'%20and%20columns%3D'rate%2Cname'&format=json&callback=parseExchangeRate");
			document.body.appendChild(script);
		}

		function parseExchangeRate(data) {
			var name = data.query.results.row.name;
			var rate = parseFloat(data.query.results.row.rate, 10);
			$('#currency_rate').val(rate);
		}

		$('#currency').on('change', function () {
			getRate("USD", $(this).val() );
		});

		getRate("USD", $('#currency').val() );
	</script>

</body>
</html>