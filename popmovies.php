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
		var useGenre = false;
		var myMovieList;
		var myDisplayList;
		
		function logout() {
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
				var jsonResults = JSON.parse(response);
				myMovieList = jsonResults;
				myDisplayList = JSON.parse(response);
				
				var col = 0;
				var rowT = "";
				
				for (var i = 0; i < jsonResults.movies.length; i++) {
					rowT += "<td width=\"20%\"><table class=\"innerMovie\"><tr><td class=\"movieTitle centerT boldT noPad\">" + jsonResults.movies[i].name + "</td></tr><tr><td class=\"centerT noPad\"><img src=\"" + jsonResults.movies[i].link + "\" width=\"100\"></td></tr></table></td>";
					col++;	
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
				
				var genreList = [];
				var genreChecks = "";
				for (var i = 0; i < myMovieList.movies.length; i++) {
					if (jQuery.inArray(myMovieList.movies[i].genre, genreList).toString() == "-1") {
						genreList.push(myMovieList.movies[i].genre);
						genreChecks += "<br><span class=\"disabledOption\"><input type=\"checkbox\" class=\"genreSelect\" name=\"genreSelector\" value=\"" + myMovieList.movies[i].genre + "\" disabled>&nbsp;" + myMovieList.movies[i].genre + "</span>";
					}
				}
				$(".filterGenre").html(genreChecks);
			});
		}
		function filter() {
			$(".filterOptions").animate({right: '0px'});
		}
		function closeFilter() {			
			$(".filterOptions").animate({right: '-300px'});
		}
		function inputFilter() {
			var checkedVals = [];
			
			$(".movieList").html("<div class=\"filterButton\"><input type=\"button\" class=\"btn-large waves-effect waves-red buttonRed lighten-1 subButton\" value=\"Filter\" onclick=\"filter();\"></div><h2 class=\"centerT topDown\">Popular Movies</h2><table class=\"movieTable\" id=\"movieTab\"></table>");
			
			if (useGenre) {
				myDisplayList.movies = [];
				$("input[name=genreSelector]:checked").each(function() {
					checkedVals.push($(this).val());
				});]
				for (var i = 0; i < myMovieList.movies.length; i++) {
					for (var j = 0; j < checkedVals.length; j++) {
						if (checkedVals[j] == myMovieList.movies[i].genre) {
							myDisplayList.movies.push(myMovieList.movies[i]);			
						}
					}
				}			
			}
			else {
				myDisplayList.movies = [];
				for (var i = 0; i < myMovieList.movies.length; i++) {
					myDisplayList.movies.push(myMovieList.movies[i]);
				}
			}
			
			var col = 0;
			var rowT = "";
			
			for (var i = 0; i < myDisplayList.movies.length; i++) {
				rowT += "<td width=\"20%\"><table class=\"innerMovie\"><tr><td class=\"movieTitle centerT boldT noPad\">" + myDisplayList.movies[i].name + "</td></tr><tr><td class=\"centerT noPad\"><img src=\"" + myDisplayList.movies[i].link + "\" width=\"100\"></td></tr></table></td>";
				col++;	
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
			$(".filterOptions").animate({right: '-300px'});
		}
		function useGenres() {
			if (useGenre) {
				$(".openOption").attr("class", "disabledOption");
				$(".genreSelect").prop("disabled", true).attr("checked", false);
				useGenre = false;
			}
			else {
				$(".disabledOption").attr("class", "openOption");
				$(".genreSelect").prop("disabled", false);
				useGenre = true;
			}
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
			</div>			
		</nav>
		<div id="pageContent" class="tall">			
			<div class="movieList centerT">
				<div class="filterButton">
					<input type="button" class="btn-large waves-effect waves-red buttonRed lighten-1 subButton" value="Filter" onclick="filter();">
				</div>
				<h2 class="centerT topDown">Popular Movies</h2>
				<table class="movieTable" id="movieTab">
					
				</table>
			</div>
		</div>
		<div class="filterOptions">			
			<span onclick="closeFilter()"><i class="material-icons close">highlight_off</i></span>
			<div class="filterInfo">
				<span class="filterTitle">GENRES&nbsp;&nbsp;<input type="checkbox" value="actionGenre" onclick="useGenres();"></span>
				<div class="filterGenre">
				</div>
				<br>
				<br>
				<input type="button" class="btn-large waves-effect waves-red buttonRed lighten-1 subButton" value="Filter" onclick="inputFilter();">
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