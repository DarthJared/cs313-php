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
<?php
	session_start();
	if (isset($_SESSION["isLoggedIn"])) {
		if ($_SESSION["isLoggedIn"] != true) {
			echo "window.location.replace('movies.php');";
		}
	}	
	else {
		echo "window.location.replace('movies.php');";
	}
?>
		function logout() {
			////alert("here");
			var username = $("#uname").val();
			var password = $("#pword").val();
			var url = "scripts.php";
			data = {'action': 'logout'};
			$.post(url, data, function (response) {
				window.location.replace("movies.php");	
			});
		}
		function getMovies() {
			var username = $("#uname").val();
			var password = $("#pword").val();
			var url = "scripts.php";
			data = {'action': 'notMyMovies'};
			$.post(url, data, function (response) {
				////alert(response);
				var jsonResults = JSON.parse(response);
				////alert(jsonResults.movies[0].name + " " + jsonResults.movies[0].link + " " + jsonResults.movies[0].genre + " " + jsonResults.movies[0].length + " " + jsonResults.movies[0].last + " " + jsonResults.movies[0].description);
				
				var col = 0;
				var rowT = "";
				
				for (var i = 0; i < jsonResults.movies.length; i++) {
					////alert("we're in");
					//add a row to the table
					rowT += "<td width=\"20%\"><table class=\"innerMovie\"><tr><td class=\"movieTitle centerT boldT noPad\">" + jsonResults.movies[i].name + "</td></tr><tr><td class=\"centerT noPad\"><img src=\"" + jsonResults.movies[i].link + "\" width=\"100\"></td></tr></table></td>";
					col++;	
					////alert(rowT);
					if (col > 4) {
						var tabAdd = document.getElementById("movieTab");
						var tabRow = tabAdd.insertRow(-1);
						tabRow.innerHTML = rowT;
						rowT = "";
						col = 0;
					}
					
				}
				if (col != 0) {
					var tabAdd = document.getElementById("movieTab");
						var tabRow = tabAdd.insertRow(-1);
						tabRow.innerHTML = rowT;
				}
			});
		}
		</script>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
	</head>
	<body class="supermanBack" onload="getMovies()">
		<nav class="coolRed" role="navigation">
			<div class="nav-wrapper container" >
				<a id="logo-container" href="movies-home.php" class="brand-logo"><span class="whiteT">Movie Manager</span></a>
				<ul class="right hide-on-med-and-down" >
					<li><a href="mymovies.php" class="whiteT">My Movies</a></li>
					<li><a href="popmovies.php" class="whiteT">Popular Movies</a></li>
					<li onclick="logout()"><a class="whiteT">Logout</a></li>
				</ul>	
				<ul id="nav-mobile" class="side-nav">
					<li><a href="mymovies.php">My Movies</a></li>
					<li><a href="popmovies.php">Popular Movies</a></li>
					<li onclick="logout()"><a>Logout</a></li>
				</ul>
				<!-- <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons white">menu</i></a>-->
			</div>			
		</nav>
		<div id="pageContent" class="tall">
			<div class="movieList centerT">
				<h2 class="centerT topDown">Popular Movies</h2>
				<table class="movieTable" id="movieTab">
					
				</table>
			</div>
		</div>
		<footer class="page-footer coolRed fixed">
			<div class="footer-copyright">
				<div class="container up">
					<i class="material-icons md-15">copyright</i>&nbsp; 2016&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Made by Jared Beagley inspired by Natasha Beagley
				</div>
			</div>
		</footer>
	</body>
</html>