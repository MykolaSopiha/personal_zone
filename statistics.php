<?php

	require 'db.php';
	
	if (!isset($_SESSION['logged_user'])) {
		header('Location: index.php');
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Статистика</title>
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="msg-box msg-box--success">
		<h2>Привет, <?php echo $_SESSION['logged_user']->login; ?></h2>
		<a class="msg-box__logout" href="index.php">Личный кабинет</a><br>
		<a class="msg-box__index" href="logout.php">Вийти</a>
	</div>
	
	<div class="full-screen">
		<div class="screen-center">
			<h3 align="center" >Статистика</h3>

			<table id="statistic_table" width="100%" cellspacing="2" cellpadding="5">
				<thead>
					<tr>
						<td>Дата</td>
						<td>Потрачено, USD</td>
						<td>Приход, USD</td>
						<td>ROI, %</td>
						<td>Profit</td>
					</tr>
				</thead>
				<tbody>
<?php

	$costs = R::findAll($costs_table);

	$day_cost = array();
	foreach ($costs as $cost) {
		$day_cost["$cost->date"]["date"] = $cost->date;
		$day_cost["$cost->date"]["spent"] += $cost->value / $cost->currency_rate;
	}

	if ( !empty($day_cost) ) {

		foreach ($day_cost as $date) {

			$total = 0;
			$roi = $total - $date['spent'] / $date['spent'];
			$profit = $total - $date['spent'];
			echo "<tr>";
			echo "<td>".$date["date"]."</td>";
			echo "<td>".sprintf("%.2f", $date["spent"])."</td>";
			echo "<td>".sprintf("%.2f", $total)."</td>";
			echo "<td>".sprintf("%.2f", $roi)."</td>";
			echo "<td>".sprintf("%.2f", $profit)."</td>";
			echo "</tr>";
		}

	}

	echo "</tbody></table>";
?>
		</div>
	</div>

	<script type="text/javascript" src="js/statistics.js"></script>
</body>
</html>