<?php
session_start();
if (isset($_SESSION['user_id']))
{
	$mysqli = require __DIR__ . "/database.php";

	if (isset($_GET['id']))
	{
		$id = $_GET['id'];

		$sql = "SELECT * FROM loan WHERE id = {$id}";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();

		$loan_amount = $row['amount'];

		$term = $row['term'];

		$remainingBal = $loan_amount;

		$rate = $row['interest_rate'];

		$monthly_rate = ($rate / 12) / 100;

		$emi = $loan_amount * ($monthly_rate * pow((1 + $monthly_rate), $term)) / (pow((1 + $monthly_rate), $term) - 1);
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
    <title>Ammortization table</title>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <style type="text/css">
    h1 {
        font-weight: lighter;
        font-size: 30px;
        color: #5E5E5E;
        margin-top: 25px;
    }

    .table_container {
        margin: auto;
        width: 90%;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
    }

    table {
        width: 100%;
        margin-top: 25px;
    }

    tr:hover {
        background-color: coral;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <div class="table_container">
        <h1>Ammortization table</h1>
        <table>
            <tr>
                <th>Beginning Balance</th>
                <th>Interest</th>
                <th>Principal</th>
                <th>Ending Balance</th>
            </tr>

            <?php for ($i=1; $i<=$term; $i++) :?>
            <?php 
				$beginningBal = $remainingBal;
				$interest = $remainingBal * $monthly_rate;
				$principal = $emi - $interest;
				$remainingBal -= $principal;
			?>
            <tr>
                <td><?= round($beginningBal, 2) ?></td>
                <td><?= round($interest, 2) ?></td>
                <td><?= round($principal, 2) ?></td>
                <td><?= round($remainingBal, 2) ?></td>
            </tr>

            <?php endfor ?>
        </table>

    </div>
</body>

</html>