<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
		<title>Movie Manager</title>
		<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script src="js/materialize.js"></script>
		<script src="js/init.js"></script>
		<script>
			function createAccount() {
				var fName = $(".fName").val();
				var lName = $(".lName").val();
				var fullName = fName + " " + lName;
				var userName = $(".uName").val();
				var email = $(".email").val();
				var password = $(".pWord1").val();
				var password2 = $(".pWord2").val();
				var error = false;
				var userExists = false;
				
				if (fName.length <= 0) {
					$(".fName").attr("placeholder", "Please enter a valid first name...");
					error = true;
				}
				if (lName.length <= 0) {
					$(".lName").attr("placeholder", "Please enter a valid last name...");
					error = true;
				}
				if (userName.length <= 0) {
					$(".uName").attr("placeholder", "Please enter a valid username...");
					error = true;
				}
				if (email.length <= 0) {
					$(".email").attr("placeholder", "Please enter a valid email...");
					error = true;
				}
				if (password.length <= 0) {
					$(".pWord1").attr("placeholder", "Please the same password into both password fields...");
					error = true;
				}
				if (password2.length <= 0) {
					$(".pWord2").attr("placeholder", "Please the same password into both password fields...");
					error = true;
				}
				if (password != password2) {
					$(".pWord1").val("").attr("placeholder", "Please the same password into both password fields...");
					$(".pWord2").val("").attr("placeholder", "Please the same password into both password fields...");
					error = true;
				}
				if (!error) {
					var url = "scripts.php";
					data = { 'action': 'checkUser', 'username': userName};
					$.post(url, data, function (response) {
						if (response == 1) {
							var url = "scripts.php";
							data = { 'action': 'addUser', 'username': userName, 'password': password, 'name': fullName, 'email': email };
							$.post(url, data, function (response) {		
								//alert(response);							
								window.location.replace("movies-home.php");						
							});
						}	
						else {
							$(".uName").val("").attr("placeholder", "This username is taken, please choose a different one...");
							error = true;
						}
					});	
				}				
			}
		</script>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
	</head>
	<body class="bondBack">
		<nav class="coolRed" role="navigation">
			<div class="nav-wrapper container" >
				<a id="logo-container" href="movies-home.php" class="brand-logo"><span class="whiteT">Movie Manager</span></a>
				<ul class="right hide-on-med-and-down" >
					<li><a href="movies.php" class="whiteT">Login</a></li>
				</ul>
			</div>
			
		</nav>
		<div id="pageContent" class="tall">
			<div class="accountAdding">				
				<h2 class="centerT topDown">Add Account</h2>
				<table class="innerAdder">
					<tr>
						<td class="halfW">First Name:</td>
						<td class="halfW">Last Name:</td>
					</tr>
					<tr>
						<td><input type="text" placeholder="First Name" class="fName"><br></td>
						<td><input type="text" placeholder="Last Name" class="lName"><br></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td>Username:</td>
					</tr>
					<tr>
						<td><input type="text" placeholder="Email" class="email"><br></td>
						<td><input type="text" placeholder="Username" class="uName"><br></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td>Repeat Password:</td>
					</tr>
					<tr>
						<td><input type="password" placeholder="Password"  class="pWord1"><br></td>
						<td><input type="password" placeholder="Password" class="pWord2"><br></td>
					</tr>
					<tr>
						<td>
							<br>
							<br>
							<input type="button" class="btn-large waves-effect waves-red buttonRed lighten-1 subButton" onclick="createAccount()" value="Create Account">					
							<br>
							<br>
						</td>
						<td>
						
						</td>	
				</table>
			</div>
		</div>
		<footer class="page-footer coolRed fixed">
			<div class="footer-copyright">
				<div class="container up">
					<i class="material-icons md-15">copyright</i>&nbsp; 2016&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Made by Jared Beagley
				</div>
			</div>
		</footer>
	</body>
</html>