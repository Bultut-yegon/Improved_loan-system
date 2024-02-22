<?php
session_start();

if (isset($_SESSION['user_id']))
{
	$mysqli = require __DIR__ . "/database.php";
	$sql = "SELECT * FROM user WHERE id = {$_SESSION['user_id']}";

	$result = $mysqli->query($sql);

	$user = $result->fetch_assoc();

	//get all loans
	$sql = "SELECT * FROM loan WHERE user_id = {$_SESSION['user_id']}";
	$result = $mysqli->query($sql);

}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Loans</title>
	<link rel="stylesheet" type="text/css" href="./dashboard.css">
	<style type="text/css">
		h1{
			font-weight: lighter;
			font-size: 30px;
			color: #5E5E5E;
			margin-top: 25px;
		}
		.table_container{
			width: 75%;
			height: 100vh;
			display: flex;
			align-items: flex-start;
			justify-content: flex-start;
			flex-direction: column;
			padding-left: 30px;
			overflow-y: auto;
			overflow-x: hidden;
		}
		table{
			width: 100%;
			margin-top: 25px;
		}
		tr:hover {background-color: coral; cursor: pointer;}

		a{
			color: black;
		}
	</style>
</head>
<body>
	<?php if(isset($user)):  ?>
	<div class="nav">
		<h4>KCB Loan Tracker</h4>
		<a href="dashboard.php">Overview</a>
		<a href="#" class="overview">Loans</a>
		<a href="new_loan.php">New Loan</a>
		<a href="logout.php"><img src="./assets/logout.png"></a>
	</div>
	<div class="table_container">
		<h1>Your Loans</h1>
		<table>
			<tr>
				<th>Name</th>
				<th>Amount</th>
				<th>Interest rate</th>
				<th>Duration</th>
				<th>Outstanding balance</th>
			</tr>
			
			<?php while ($row = $result->fetch_assoc()) :?>
				<tr>
					<td><?= $row['loan_name']?></td>
					<td><?= $row['amount']?></td>
					<td><?= $row['interest_rate']?></td>
					<td><?= $row['term']?> Months</td>
					<td>Ksh. <?= $row['balance']?></td>
					<td><a href="loan_page.php?id=<?= $row['id'] ?>">View</a></td>
				</tr>
			<?php endwhile; ?>

		</table>
		<?php else: ?>
		<h1>Home</h1>
		<link rel="stylesheet" type="text/css" href="form.css">
		<p><a href="login.php">Log in</a> or <a href="signup.html">Sign up</a></p>
		<?php endif; ?>
	</div>
</body>
</html>