<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
		<title>Movie Manager</title>
		<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script>
<?php
	session_start();
	if (isset($_SESSION["isLoggedIn"])) {
		if ($_SESSION["isLoggedIn"] == true) {
			echo "window.location.replace('movies-home.php');";
		}
	}	
?>
		function login() {
			//alert("here");
			var username = $("#uname").val();
			var password = $("#pword").val();
			var url = "scripts.php";
			data = {'action': 'login', 'username': username, 'password': password};
			$.post(url, data, function (response) {
				//alert(response);
				if (response == 1) {
					window.location.replace("movies-home.php");	
				}
				else {
					$(".invalidT").css("display","block");
				}
			});
		}
		$(window).ready(function() {
			//alert("here");
			$('#uname').keypress(function(e){
				if(e.keyCode==13)
					$('#loginBut').click();
			});
			$('#pword').keypress(function(e){
				if(e.keyCode==13)
					$('#loginBut').click();
			});
		});
		</script>
		<script src="js/materialize.js"></script>
		<script src="js/init.js"></script>
		
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>

	</head>
	<body id="logBack">
		<nav class="coolRed" role="navigation">
			<div class="nav-wrapper container" >
				<a id="logo-container" href="index.php" class="brand-logo"><span class="whiteT">Movie Manager</span></a>				
			</div>
		</nav>
		<div id="loginPage">
			<div id="loginBox">
				<form action="" method="post">
					<h1 id="logTitle">LOGIN</h1>
					&nbsp;Username:<br>
					<input type="text" class="whiteBack" placeholder="Username" id="uname"><br>
					&nbsp;Password:<br>
					<input type="password" class="whiteBack" id="pword" placeholder="Password"><br>
					<div class="row center">
						<a href="comingSoon.php" id="createAccount" class="btn-large waves-effect waves-red buttonRed lighten-1">Create Account</a>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" id="loginBut" class="btn-large waves-effect waves-red buttonRed lighten-1 subButton" value="Login" onclick="login();">
					</div>
					<div class="invalidT">INVALID USERNAME OR PASSWORD</div>
				</form>
			</div>
		</div>
		<footer class="page-footer coolRed movie">
			<div class="footer-copyright">
				<div class="container up">
					<i class="material-icons md-15">copyright</i>&nbsp; 2016&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Made by Jared Beagley inspired by Natasha Beagley
				</div>
			</div>
		</footer>
	</body>
</html>