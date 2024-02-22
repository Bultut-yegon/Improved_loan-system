<?php 
$is_invalid = false;
if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	$mysqli = require __DIR__ . "/database.php";
	$sql = sprintf("SELECT * FROM user WHERE email='%s'", $mysqli->real_escape_string($_POST['email']));
	
	$result = $mysqli->query($sql);

	$user = $result->fetch_assoc();

	if ($user)
	{
		if (password_verify($_POST['password'], $user['password_hash']))
		{
			session_start();
			session_regenerate_id();
			
			$_SESSION['user_id'] = $user['id'];
			header("Location: dashboard.php");
			exit;
		}
	}
	$is_invalid = true;
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="./form.css">
	<style type="text/css">
		.error{
			width: 70%;
			margin: auto;
			background-color: #FF8989;
			padding: 10px;
		}
		.container-login
		{
			height: 350px;
		}
		em{
			color: #fff;
		}
	</style>
</head>
<body>
	<div class="container-login">
		<h1>Login</h1>
		<?php if ($is_invalid):?>
			<div class="error">
		 		<em>Invalid login</em>
			</div>
		<?php endif; ?>
		<form method="post">
			<div>
				<label for="email">email </label>
				<input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '' )?>">
			</div>	

			<div>
				<label for="username">Password</label>
				<input type="password" name="password">
			</div>	

			<button>Log in</button>
		</form>
	</div>
		<p>Don't have an account? <a href="signup.html">Sign up</a></p>
</body>
</html>