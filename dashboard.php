<?php 

session_start();
if (isset($_SESSION['user_id']))
{
	$mysqli = require __DIR__ . "/database.php";
	$sql = "SELECT * FROM user WHERE id = {$_SESSION['user_id']}";

	$result = $mysqli->query($sql);

	$user = $result->fetch_assoc();


	//Calculate data to be displayed

	// total borrowed
	$sql = "SELECT SUM(amount) AS total FROM loan WHERE user_id = {$_SESSION['user_id']}";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	if ($result->num_rows > 0)
	{
		$totalBorrowed = $row['total'];
	}else
		$totalBorrowed = 0;

	//total loans
	$sql = "SELECT COUNT(*) AS count FROM loan WHERE user_id = {$_SESSION['user_id']}";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	if ($result->num_rows > 0)
	{
		$totalLoans = $row['count'];
	}else
		$totalLoans = 0;

	//total paid
	$sql = "SELECT SUM(amount) AS total FROM payment WHERE user_id = {$_SESSION['user_id']}";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	if ($result->num_rows > 0)
	{
		$totalPaid = $row['total'];
	}else
		$totalPaid = 0;

	//outstanding payment
	$sql = "SELECT SUM(balance) AS total FROM loan WHERE user_id = {$_SESSION['user_id']}";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	if ($result->num_rows > 0)
	{
		$totalBalance = $row['total'];
	}else
		$totalBalance = 0;

	
	//calculate percentage
	$sql = "SELECT SUM(total_principal) AS total FROM loan WHERE user_id = {$_SESSION['user_id']}";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	
	
	if ($result->num_rows > 0)
	{
		$totalPrincipal = $row['total'];
		if ($totalPrincipal != 0)
			$percentage = ($totalPrincipal - $totalBalance) / $totalPrincipal * 100;
		else
			$percentage = 0;
	}
	else
		$percentage = 0;

	//get recent 5 payments
	$sql = "SELECT * FROM payment WHERE user_id = {$_SESSION['user_id']} LIMIT 5";
	$result = $mysqli->query($sql);

}

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <!-- <link rel="stylesheet" href="https://classless.de/classless.css"> -->
    <link rel="stylesheet" type="text/css" href="./dashboard.css">

    <script type="text/javascript" defer>
    document.addEventListener("DOMContentLoaded", function() {
        const elem = document.getElementById('time');

        function updateTime() {
            const date = new Date();
            const currentTime = date.toDateString() + " " + date.getHours() + ":" + date.getMinutes();
            elem.textContent = currentTime;
        }
        updateTime();
        setInterval(updateTime, 1000);
    });
    </script>
</head>

<body>
    <?php if(isset($user)):  ?>
    <div class="header">
        <p class="time" id="time"></p>
        <img src="./assets/user.png">
        <p class="username"><?= htmlspecialchars($user['name']) ?></p>
    </div>
    <div class="nav">
        <h4>KCB Loan Tracker</h4>
        <a href="#" class="overview">Overview</a>
        <a href="loans.php">Loans</a>
        <a href="new_loan.php">New Loan</a>
        <a href="./logout.php"><img src="./assets/logout.png"></a>
    </div>
    <div class="content">
        <h1 class="item-1">Welcome back, <?= htmlspecialchars($user['name']) ?></h1>
        <div class="item-2">
            <h1>Total Borrowed:</h1>
            <h1>Ksh. <?= number_format($totalBorrowed) ?></h1>
            <hr>
            <div class="semi-donut" style="--percentage: <?= number_format($percentage) ?>">
                <h1><span><?= number_format($percentage) ?>%</span> paid</h1>
            </div>
            <h1>Ksh. <?= number_format($totalPaid) ?></h1>

        </div>
        <div class="item-3">
            <p>
            <h1>Total Loans: <?= number_format($totalLoans) ?></h1>
            </p>
        </div>
        <div class="item-4">
            <p>
            <h1>Outstanding payment:</h1>
            <h1>Ksh. <?= number_format($totalBalance) ?></h1>
            </p>
        </div>

        <div class="item-5">
            <h1>Recent payments</h1>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) :?>
                <tr>
                    <td><?= $row['loan_name'] ?></td>
                    <td><?= $row['amount'] ?></td>
                    <td><?= $row['date'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

    </div>
    <?php else: ?>
    <h1>Home</h1>
    <link rel="stylesheet" type="text/css" href="form.css">
    <p><a href="login.php">Log in</a> or <a href="signup.html">Sign up</a></p>
    <?php endif; ?>

</body>

</html>