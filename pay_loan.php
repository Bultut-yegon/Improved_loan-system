<?php 
session_start();

if (isset($_SESSION['user_id']))
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id']))
	{
		$mysqli = require __DIR__ . "/database.php";
		$id = $_GET['id'];

		//data validation
		if (empty($_POST['payment-date'])){
			die("date is required ");
		}

		if (empty($_POST['payment-amount'])){
			die("payment-amount is required ");
		}
		if ($_POST['payment-amount'] <= 0)
		{
			die("payment-amount should be greater than zero");
		}

		//insert data to the database

		//get loan name
		$sql = "SELECT * FROM loan WHERE id = {$id}";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();

		$loan_name = $row['loan_name'];


		//Create payment

		$sql = "INSERT INTO payment(user_id, loan_id, amount, date, loan_name) VALUES(?, ?, ?, ?, ?)";

		$stmt = $mysqli->stmt_init();
		if (! $stmt->prepare($sql))
		{
			die("SQL error: " . $mysqli->error);
		}

		$stmt->bind_param("iiiss", $_SESSION['user_id'], $id, $_POST['payment-amount'], $_POST['payment-date'], $loan_name);

		if ($row['balance'] >= $_POST['payment-amount'])
		{
			if ($stmt->execute())
			{
				// update the loan balance
				$newBal = $row['balance'] - $_POST['payment-amount'];

				if ($newBal < 0)
					$newBal = 0;

				$sql = "UPDATE loan SET balance = {$newBal} WHERE id = {$id}";
				$mysqli->query($sql);
				header("Location: loan_page.php?id={$id}");
				exit;
			}
			else
				die($mysqli->error . " " . $mysqli->errno);
		}
		else{
			$newBal = $row['balance'];

			if ($newBal == 0)
				echo "<div style='width: 100%; text-align: center'><em style='color: red;'>Loan fully paid</em></div>";
			else
			echo "<div style='width: 100%; text-align: center'><em style='color: red;'>Max payment is Ksh. {$newBal}</em></div>";
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
    <title>Pay Loan</title>
    <link rel="stylesheet" type="text/css" href="./form.css">
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script type="text/javascript" src="validate_loan.js" defer></script>
</head>

<body>
    <div class="container-pay">
        <form method="post" id="form">
            <h1>Pay Loan</h1>
            <div>
                <label for="payment-date">Payment Date</label>
                <input type="date" name="payment-date" id="payment-date">
            </div>
            <div>
                <label for="payment-amount">Payment Amount</label>
                <input type="number" name="payment-amount" id="payment-amount">
            </div>
            <button>Submit</button>
        </form>
    </div>
</body>

</html>