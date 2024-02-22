<?php 
session_start();

if (isset($_SESSION['user_id']))
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$mysqli = require __DIR__ . "/database.php";

		//data validation
		if (empty($_POST['loan-name'])){
			die("Name is required ");
		}
		if (empty($_POST['loan-amount'])){
			die("loan amount is required ");
		}
		if (empty($_POST['interest-rate'])){
			die("interest-rate is required ");
		}
		if (empty($_POST['term'])){
			die("term is required ");
		}
		if (empty($_POST['start-date'])){
			die("date is required ");
		}
		if ($_POST['loan-amount'] <= 0)
		{
			die("Loan amount should be greater than zero");
		}
		if ($_POST['interest-rate'] <= 0)
		{
			die("interest-rate should be greater than zero");
		}
		if ($_POST['term'] <= 0)
		{
			die("Term should be greater than zero");
		}

		//calculate other data

		function calculateLoan($loan_amount, $term, $rate)
		{
			$monthly_rate = ($rate / 12) / 100;
			$emi = $loan_amount * ($monthly_rate * pow((1 + $monthly_rate), $term)) / (pow((1 + $monthly_rate), $term) - 1);

			$total_interest = 0;
			$remainingBal = $loan_amount;

			for ($i=1; $i<=$term; $i++){
				$interest = $remainingBal * $monthly_rate;
				$principal = $emi - $interest;
				$remainingBal -= $principal;
				$total_interest += $interest;
			}
			
			$total_principal = $loan_amount + $total_interest;
			$result = array(round($total_interest,2), round($total_principal,2), round($emi,2));
			return $result;
		}



		$ans = calculateLoan($_POST['loan-amount'], $_POST['term'], $_POST['interest-rate']);


		//calculate total_interest
		$total_interest= $ans[0];

		//calculate total_principal
		$total_principal = $ans[1];

		//calculate emi
		$emi = $ans[2];

		//calculate balance
		$balance = $total_principal;


		//insert data to the database

		$sql = "INSERT INTO loan (user_id, loan_name, amount, interest_rate, term, date, balance, total_interest, total_principal, emi)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$stmt = $mysqli->stmt_init();

		if (! $stmt->prepare($sql))
		{
			die("SQL error: " . $mysqli->error);
		}

		$stmt->bind_param('isiiisiiii', $_SESSION['user_id'], $_POST['loan-name'], $_POST['loan-amount'], $_POST['interest-rate'], $_POST['term'], $_POST['start-date'], $balance, $total_interest, $total_principal, $emi);

		if ($stmt->execute())
		{
			header("Location: loans.php");
			exit;
		}
		else{
			die($mysqli->error . " " . $mysqli->errno);
		}


	}
}



else{
	header("Location: dashboard.php");
	exit;
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Loans</title>
	<link rel="stylesheet" type="text/css" href="./dashboard.css">
	<link rel="stylesheet" type="text/css" href="./form.css">
	<style type="text/css">
		body{
			background-color: #EBEBEB;
		}
	</style>
	<script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer ></script>
	<script type="text/javascript" src="validate_payment.js" defer></script>
</head>
<body>
	<div class="nav">
		<h4>KCB Loan Tracker</h4>
		<a href="dashboard.php">Overview</a>
		<a href="loans.php">Loans</a>
		<a href="#" class="overview">New Loan</a>
		<a href="logout.php"><img src="./assets/logout.png"></a>
	</div>

	<div class="container-loan">
		<h1>Create Loan</h1>
		<form method="post" id="form">
		<div>
			<label for="loan-name">Loan Name</label>
			<input type="text" name="loan-name" id="loan-name">
		</div>	
		<div>
			<label for="loan-amount">Loan Amount</label>
			<input type="number" name="loan-amount" id="loan-amount">
		</div>	
		<div>
			<label for="interest-rate">Interest Rate (APR)</label>
			<input type="number" name="interest-rate" id="interest-rate">
		</div>
		<div>
			<label for="term">Term (Months)</label>
			<input type="number" name="term" id="term">
		</div>
		<div>
			<label for="start-date">Date</label>
			<input type="date" name="start-date" id="start-date">
		</div>	
		<button>Submit</button>
	</form>
	</div>
	
</body>
</html>