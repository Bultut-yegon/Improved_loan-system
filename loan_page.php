<?php 
session_start();
if (isset($_SESSION['user_id']))
{
	$mysqli = require __DIR__ . "/database.php";
	$sql = "SELECT * FROM user WHERE id = {$_SESSION['user_id']}";
	$result = $mysqli->query($sql);
	$user = $result->fetch_assoc();

	if (isset($_GET['id']))
	{
		$id = $_GET['id'];


		//get Loan details
		$sql = "SELECT * FROM loan WHERE id = {$id}";
		$result = $mysqli->query($sql);

		//get all payments
		$sql = "SELECT * FROM payment WHERE loan_id = {$id}";
		$payments = $mysqli->query($sql);

		//get data
		$sql = "SELECT * FROM loan WHERE id = {$id}";
		$block = $mysqli->query($sql);
		$data = $block->fetch_assoc();
		
		//calculate percentage
		if ($block->num_rows > 0)
		{
			$int_per = round(($data['total_interest'] / $data['total_principal']) * 100, 2);
		}
		else{
			$int_per = 20;
		}

	}
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loan Page</title>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <style type="text/css">
    h1 {
        font-weight: lighter;
        font-size: 15px;
        color: #5E5E5E;
        margin-top: 10px;
        text-align: left;
    }

    .table_container {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
    }

    table {
        width: 95%;
        margin-top: 15px;
    }

    tr:hover {
        background-color: coral;
    }

    .data-panel {
        height: 250px;
        width: 95%;
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 10px;
        margin-top: 15px;
    }

    .actions {
        height: 250px;
        width: 250px;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }

    .data {
        height: 250px;
        width: 250px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .actions a {
        text-decoration: none;
        background-color: coral;
        padding: 10px;
        text-align: center;
        border-radius: 3px;
        color: white;
    }
    </style>
</head>

<body>
    <?php if(isset($user)):  ?>
    <div class="table_container">
        <h1>Your Loan</h1>
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
                <td>Ksh. <?= $row['amount']?></td>
                <td><?= $row['interest_rate']?></td>
                <td><?= $row['term']?> Months</td>
                <td>Ksh. <?= $row['balance']?></td>
            </tr>
            <?php endwhile; ?>

        </table>
        <h1>Your Payments</h1>
        <table>
            <tr>
                <th>Date</th>
                <th>Amount</th>
            </tr>
            <?php while ($row1 = $payments->fetch_assoc()) :?>
            <tr>
                <td><?= $row1['date']?></td>
                <td>Ksh. <?= $row1['amount']?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <div class="data-panel">
            <div class="chart"><canvas percentage="<?= $int_per ?>" id="pie-chart"></canvas></div>

            <div class="data">
                <h1>Monthly Payment:</h1>
                <h1>Ksh. <?= $data['emi'] ?></h1>
                <h1>Total Payment:</h1>
                <h1>Ksh. <?= $data['total_principal'] ?></h1>
                <h1>Total Interest:</h1>
                <h1>Ksh. <?= $data['total_interest'] ?></h1>
            </div>
            <div class="actions">
                <a href="pay_loan.php?id=<?=$id?>">Pay</a>
                <a href="delete.php?id=<?=$id?>">Delete</a>
                <a href="ammort.php?id=<?=$id?>">Ammortization</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript">
    let chart = document.getElementById('pie-chart');

    var int_per = chart.getAttribute('percentage');

    new Chart(document.getElementById('pie-chart'), {
        type: 'pie',
        data: {
            labels: ['Principal', 'Interest'],
            datasets: [{
                backgroundColor: ["#89FF8F", "#FF8989"],
                data: [(100 - int_per), int_per]
            }]
        },
        options: {
            title: {
                display: false,
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
    </script>
    <?php else: ?>
    <h1>Home</h1>
    <link rel="stylesheet" type="text/css" href="form.css">
    <p><a href="login.php">Log in</a> or <a href="signup.html">Sign up</a></p>
    <?php endif; ?>
</body>

</html>