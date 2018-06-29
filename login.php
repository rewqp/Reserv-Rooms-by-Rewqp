<?php
	session_start();

	if ( isset($_POST['login']) && isset($_POST['pass']) ) {
		if ($_POST['login'] == 'admin' && $_POST['pass'] == 'pass') {
			$_SESSION['joined'] = true;
			$_SESSION['username'] = $_POST['login'];
			header("Location: index.php");
		}
	}
	//include '../db/db_conect.php';
?>
 
<html>
	<head>
	<title>Вход в систему</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/login.css"> 
	</head>

	<body>
		<div id="header">
			<div class="inHeaderLogin"></div>
		</div>

		<div id="loginForm">
			<div class="headLoginForm">
			</br>
			</br>
			<span class="LogoAut">BMS<span class="RedPlus">+</span>D. Резервация</span>
			</div>
				<div class="fieldLogin">
					<form method="POST" action="login.php">
							<input name="login" type="text" class="login" placeholder="Login"><br>
							<input name="pass" type="password" class="login" placeholder="Password"><br>
							<input type="image" class="Deya" src="img/reds.svg">
					</form>
				</div>
		</div>
	</body>
</html>