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
		var genres = Array();
		var myMovieList;
		var commentBox;
		
		function logout() {
			//alert("here");
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
			data = {'action': 'myMovies'};
			$.post(url, data, function (response) {
				//alert(response);
				var jsonResults = JSON.parse(response);
				myMovieList = jsonResults;
				//alert(jsonResults.movies[0].name + " " + jsonResults.movies[0].link + " " + jsonResults.movies[0].genre + " " + jsonResults.movies[0].length + " " + jsonResults.movies[0].last + " " + jsonResults.movies[0].description + " " + jsonResults.movies[0].id );
				
				var col = 1;
				var rowCount = 0;
				var rowT = "";
				
				for (var i = 0; i < jsonResults.movies.length; i++) {
					//alert("we're in");
					if (rowCount == 0) {
						//add cells to first row
						var row = document.getElementById("firstRow");
						var name = row.insertCell(-1);
						
						if (!jsonResults.movies[i].last || 0 === jsonResults.movies[i].last) {
							var adding = "<table class=\"innerMovie\" onclick=\"showInfo($(this))\"><tr><td class=\"movieTitle centerT boldT noPad\">" + jsonResults.movies[i].name + "</td></tr><tr><td class=\"centerT noPad\"><input type=\"text\" value=\"" + jsonResults.movies[i].id + "\" style=\"display:none\"><img src=\"" + jsonResults.movies[i].link + "\" width=\"100\"></td></tr><tr><td class=\"centerT noPad lastW\">Not Yet Watched!</td></tr></table>";
							name.innerHTML = adding;							
						}
						else {
							var adding = "<table class=\"innerMovie\" onclick=\"showInfo($(this))\"><tr><td class=\"movieTitle centerT boldT noPad\">" + jsonResults.movies[i].name + "</td></tr><tr><td class=\"centerT noPad\"><input type=\"text\" value=\"" + jsonResults.movies[i].id + "\" style=\"display:none\"><img src=\"" + jsonResults.movies[i].link + "\" width=\"100\"></td></tr><tr><td class=\"centerT noPad lastW\">Last Watched: " + jsonResults.movies[i].last + "</td></tr></table>";
							name.innerHTML = adding;
						}
						col++;
						//alert(col);
					}
					else {
						//add a row to the table
						if (!jsonResults.movies[i].last || 0 === jsonResults.movies[i].last) {
							rowT += "<td width=\"20%\"><table class=\"innerMovie\" onclick=\"showInfo($(this))\"><tr><td class=\"movieTitle centerT boldT noPad\">" + jsonResults.movies[i].name + "</td></tr><tr><td class=\"centerT noPad\"><input type=\"text\" value=\"" + jsonResults.movies[i].id + "\" style=\"display:none\"><img src=\"" + jsonResults.movies[i].link + "\" width=\"100\"></td></tr><tr><td class=\"centerT noPad lastW\">Not Yet Watched!</td></tr></table></td>";							
						}
						else {
							rowT += "<td width=\"20%\"><table class=\"innerMovie\" onclick=\"showInfo($(this))\"><tr><td class=\"movieTitle centerT boldT noPad\">" + jsonResults.movies[i].name + "</td></tr><tr><td class=\"centerT noPad\"><input type=\"text\" value=\"" + jsonResults.movies[i].id + "\" style=\"display:none\"><img src=\"" + jsonResults.movies[i].link + "\" width=\"100\"></td></tr><tr><td class=\"centerT noPad lastW\">Last Watched: " + jsonResults.movies[i].last + "</td></tr></table></td>";
						}
						col++;	
						if (col > 4) {
							var tabAdd = document.getElementById("movieTab");
							var tabRow = tabAdd.insertRow(-1);
							tabRow.innerHTML = rowT;
							rowT = "";
						}
					}
					if (col > 4) {
						col = 0;
						rowCount++;
					}
					
				}
				if (col != 0) {
					var tabAdd = document.getElementById("movieTab");
					var tabRow = tabAdd.insertRow(-1);
					tabRow.innerHTML = rowT;
				}
				for (var i = 0; i < jsonResults.movies.length; i++) {
					if (contains(genres, jsonResults.movies[i].genre)) {
						//alert("here");
					}
					else {
						genres.push(jsonResults.movies[i].genre);
					}
				}
				//alert(genres);
			});	
			// var drawerT = 
			// $("#filterDrawer").text("<p>GENRES</p>
		}
		var isOpen = false;
		function openFilter() {
			if (isOpen) {
				
			}
			else {
				
			}
		}
		function contains(a, obj) {
			for (var i = 0; i < a.length; i++) {
				if (a[i] === obj) {
					return true;
				}
			}
			return false;
		}
		function closeInfo() {
			$(".coverBack").hide();
		}
		// function adjustPic() {
			// var percent = screen.height * 0.7;
			// $(".shortPic").css("height", percent);
		// }
		function showInfo(movie) {
			$(".coverBack").show();
			var name = movie.find(".movieTitle").text();
			//alert(name);
			var movIndex = 0;
			for (var i = 0; i < myMovieList.movies.length; i++) {
				if (name == myMovieList.movies[i].name) {
					movIndex = i;
				}
			}
			$(".movieInfoTitle").text(name);
			$(".shortPic").attr("src", myMovieList.movies[movIndex].link);
			
			if (!myMovieList.movies[movIndex].last || 0 === myMovieList.movies[movIndex].last) {
				$(".movInfLast").text("Not Yet Watched!");
			}
			else {
				$(".movInfLast").text("Last Watched: " + myMovieList.movies[movIndex].last);
			}
			
			if (!myMovieList.movies[movIndex].length || 0 === myMovieList.movies[movIndex].length) {
				$(".movInfLength").text("Length Not Known");
			}
			else {
				$(".movInfLength").text("Length: " + myMovieList.movies[movIndex].length);
			}
			
			if (!myMovieList.movies[movIndex].description || 0 === myMovieList.movies[movIndex].description) {
				$(".commentsText").hide();
				$(".commentsInput").show();
				commentBox = true;
			}
			else {
				$(".commentsText").show();
				$(".commentsText").text(myMovieList.movies[movIndex].description);
				$(".commentsInput").hide();
				commentBox = false;
			}
			
		}
		function editCom() {
			if (commentBox) {
				//update comments in db
				//refresh comments displayed
			}
			else {
				$(".commentsInput").show().val(myMovieList.movies[movIndex].description);
			}
		}
		function watched() {
			var name = $(".movieInfoTitle").text();
			//alert(name);
			var movIndex = 0;
			for (var i = 0; i < myMovieList.movies.length; i++) {
				if (name == myMovieList.movies[i].name) {
					movIndex = i;
				}
			}
		}
		// $(window).ready(function() {
			// //alert("here");
			// $(".innerMovie").click(function() {
				// alert("here");
				// $(".coverBack").show();
			// });
		// });
		</script>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
	</head>
	<body class="avengersBack" onload="getMovies()">
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
				<!--<a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons white">menu</i></a>-->
			</div>			
		</nav>
		<div id="pageContent" class="tall">
			<div class="movieList">
				<!--<div id="filter" onclick="openFilter()">FILTER</div>-->
				<h2 class="centerT topDown">My Movies</h2>
				<table class="movieTable" id="movieTab">
					<tr id="firstRow">
						<td width="20%">
							<table class="innerMovie">
								<tr>
									<td class="movieTitle centerT boldT noPad">ADD MOVIE</td>
								</tr>
								<tr>
									<td class="centerT noPad"><div class="movieAdd"><table class="movieAddTab" ><tr><td class="moviePlus">+</td></tr></table></div></td>
								</tr>
								<tr>
									<td class="centerT noPad lastW"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="coverBack" style="display: none">
			<div class="movieInform">
				<table class="movieInfoTab">
					<tr>
						<td width="25%"><img src="images/avengersSmall.jpg" width="100%" class="shortPic"></td>
						<td width="75%" class="topCell">
							<table class="infoTab">
								<tr>
									<td class="movieInfoTitle" colspan="2">
										
									</td>
								</tr>
								<tr>
									<td class="middle boldT movInfLength">
										
									</td>
									<td class="middle boldT movInfLast">
										
									</td>
								</tr>
								<tr>	
									<td colspan="2">
										Comments:
										<br>
										<span class="commentsText"></span>
										<textArea class="commentsInput">
										</textArea>
									</td>
								</tr>
								<tr>
									<td>
										<button type="button" onclick="editCom()">Add/Edit Comment</button>
									</td>
									<td>
										<button type="button" onclick="watched()">Watched</button>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<span onclick="closeInfo()"><i class="material-icons close">highlight_off</i></span>
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