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
		var allGenres;
		var myMovieList;
		var commentBox;
		var selectedId;
		var movIndex;
		
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
			data = {'action': 'myMovies'};
			$.post(url, data, function (response) {
				////alert(response);
				var jsonResults = JSON.parse(response);
				myMovieList = jsonResults;
				////alert(jsonResults.movies[0].name + " " + jsonResults.movies[0].link + " " + jsonResults.movies[0].genre + " " + jsonResults.movies[0].length + " " + jsonResults.movies[0].last + " " + jsonResults.movies[0].description + " " + jsonResults.movies[0].id );
				
				var col = 1;
				var rowCount = 0;
				var rowT = "";
				
				for (var i = 0; i < jsonResults.movies.length; i++) {
					////alert("we're in");
					if (rowCount == 0) {
						//add cells to first row
						var row = document.getElementById("firstRow");
						var name = row.insertCell(-1);
						
						if (!jsonResults.movies[i].last || 0 === jsonResults.movies[i].last) {
							var adding = "<table class=\"innerMovie\" onclick=\"showInfo($(this))\"><tr><td class=\"movieTitle centerT boldT noPad\">" + jsonResults.movies[i].name + "</td></tr><tr><td class=\"centerT noPad\"><input type=\"text\" class=\"movieId\" value=\"" + jsonResults.movies[i].id + "\" style=\"display:none\"><img src=\"" + jsonResults.movies[i].link + "\" width=\"100\"></td></tr><tr><td class=\"centerT noPad lastW\">Not Yet Watched!</td></tr></table>";
							name.innerHTML = adding;							
						}
						else {
							var adding = "<table class=\"innerMovie\" onclick=\"showInfo($(this))\"><tr><td class=\"movieTitle centerT boldT noPad\">" + jsonResults.movies[i].name + "</td></tr><tr><td class=\"centerT noPad\"><input type=\"text\" class=\"movieId\" value=\"" + jsonResults.movies[i].id + "\" style=\"display:none\"><img src=\"" + jsonResults.movies[i].link + "\" width=\"100\"></td></tr><tr><td class=\"centerT noPad lastW\">Last Watched: " + jsonResults.movies[i].last + "</td></tr></table>";
							name.innerHTML = adding;
						}
						col++;
						////alert(col);
					}
					else {
						//add a row to the table
						if (!jsonResults.movies[i].last || 0 === jsonResults.movies[i].last) {
							rowT += "<td width=\"20%\"><table class=\"innerMovie\" onclick=\"showInfo($(this))\"><tr><td class=\"movieTitle centerT boldT noPad\">" + jsonResults.movies[i].name + "</td></tr><tr><td class=\"centerT noPad\"><input type=\"text\" class=\"movieId\" value=\"" + jsonResults.movies[i].id + "\" style=\"display:none\"><img src=\"" + jsonResults.movies[i].link + "\" width=\"100\"></td></tr><tr><td class=\"centerT noPad lastW\">Not Yet Watched!</td></tr></table></td>";							
						}
						else {
							rowT += "<td width=\"20%\"><table class=\"innerMovie\" onclick=\"showInfo($(this))\"><tr><td class=\"movieTitle centerT boldT noPad\">" + jsonResults.movies[i].name + "</td></tr><tr><td class=\"centerT noPad\"><input type=\"text\" class=\"movieId\" value=\"" + jsonResults.movies[i].id + "\" style=\"display:none\"><img src=\"" + jsonResults.movies[i].link + "\" width=\"100\"></td></tr><tr><td class=\"centerT noPad lastW\">Last Watched: " + jsonResults.movies[i].last + "</td></tr></table></td>";
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
						////alert("here");
					}
					else {
						genres.push(jsonResults.movies[i].genre);
					}
				}
				////alert(genres);
			});	
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
			window.location.replace('mymovies.php');
			//$(".coverBack").hide();
		}
		// function adjustPic() {
			// var percent = screen.height * 0.7;
			// $(".shortPic").css("height", percent);
		// }
		function showInfo(movie) {
			$(".coverBack").show();
			var name = movie.find(".movieTitle").text();
			////alert(name);			
			for (var i = 0; i < myMovieList.movies.length; i++) {
				if (name == myMovieList.movies[i].name) {
					movIndex = i;
				}
			}
			$(".movieInfoTitle").text(name);
			$(".shortPic").attr("src", myMovieList.movies[movIndex].link);
			$(".movInfGenre").text(myMovieList.movies[movIndex].genre);
			
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
				$(".commentsInput").show().val('');
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
				var com = $(".commentsInput").val();
				var url = "scripts.php";
				data = {'action': 'addComment', 'comment': com, 'movieId': myMovieList.movies[movIndex].id };
				$.post(url, data, function (response) {					
					$(".commentsText").show().text(com);
					$(".commentsInput").hide();
					commentBox = false;
				});
			}
			else {
				$(".commentsInput").show().val($(".commentsText").text());
				$(".commentsText").hide();
				commentBox = true;
			}
		}
		function watched() {
			var name = $(".movieInfoTitle").text();
			////alert(name);
			var com = $(".commentsInput").val();
			var url = "scripts.php";
			var today = new Date();
			data = {'action': 'watch', 'movieId': myMovieList.movies[movIndex].id, 'date': today.toDateString() };
			$.post(url, data, function (response) {
				////alert(response);
				$(".movInfLast").text("Last Watched: " + today.toDateString());
			});			
		}
		
		function addMovie() {
			////alert("im in");
			var nmName = $(".newMovName").val();
			var nmLength = $(".hours").val() + ":" + $(".minutes").val();
			var nmLink = $(".newImg").val();
			var nmGenre = $("input[name=genreSel]:checked").val();
			if (nmGenre == "Other") {
				var nmGenreName = $(".newGenre").val();
				if (nmGenreName.length > 0) {
					////alert("im in");
					if (nmName.length > 0) {
						if (nmLink.length > 0) {
							//query to add to genre and genre lookup
							var url = "scripts.php";
							var data = {'action': 'addGenre', 'genreName': nmGenreName};
							$.post(url, data, function (response) {
								////alert(response);							
							});	 
							
							var data2 = {'action': 'addMovie', 'genreName': nmGenreName, 'length': nmLength, 'link': nmLink, 'name': nmName };
							$.post(url, data2, function (response) {
								////alert(response);	
								closeInfo();								
							});
						}
						else {
							$(".newImg").attr("placeholder", "Please Enter a Valid Image Link...");
						}
					}	
					else {
						$(".newMovName").attr("placeholder", "Please Enter a Valid Movie Name...");
						if (nmLink.length <= 0) {
							$(".newImg").attr("placeholder", "Please Enter a Valid Image Link...");
						}
					}
				}
				else {
					//show error that must enter genre name			
					$(".newGenre").attr("placeholder", "Please Enter a Valid Genre...");		
					if (nmName.length <= 0) {			
						$(".newMovName").attr("placeholder", "Please Enter a Valid Movie Name...");						
					}
					if (nmLink.length <= 0) {
						$(".newImg").attr("placeholder", "Please Enter a Valid Image Link...");
					}
				}
			}
			else {
				if (nmName.length > 0) {
					if (nmLink.length > 0) {
						//query to add to genre and genre lookup
						var url = "scripts.php"; 
						
						var data2 = {'action': 'addMovie', 'genreName': nmGenre, 'length': nmLength, 'link': nmLink, 'name': nmName };
						$.post(url, data2, function (response) {
							////alert(response);	
							closeInfo();
						});	 
					}
					else {
						$(".newImg").attr("placeholder", "Please Enter a Valid Image Link...");
					}
				}	
				else {
					$(".newMovName").attr("placeholder", "Please Enter a Valid Movie Name...");
					if (nmLink.length <= 0) {
						$(".newImg").attr("placeholder", "Please Enter a Valid Image Link...");
					}
				}
			}
		}
		function showAdder() {
			var url = "scripts.php";
			data = {'action': 'getGenre'};
			$.post(url, data, function (response) {
				////alert(response);
				var jsonResults = JSON.parse(response);
				////alert(allGenres);
				allGenres = jsonResults;
				var genreL = "";
				for (var i = 0; i < allGenres.genres.length; i++) {
					genreL += "<input type=\"radio\" value=\"" + allGenres.genres[i].name + "\" name=\"genreSel\">" + allGenres.genres[i].name + "<br>";				
				}
				$(".genreList").html(genreL);
			});
			$(".adderBack").show();
			////alert(genres);
			
		}		
		function deleteMov() {
			var url = "scripts.php";
			data = { 'action': 'delete', 'movieId': myMovieList.movies[movIndex].id };
			$.post(url, data, function (response) {
				////alert(response);
				closeInfo();
			});
		}
		
		// $(window).ready(function() {
			// ////alert("here");
			// $(".innerMovie").click(function() {
				// //alert("here");
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
						<td width="20%" onclick="showAdder()">
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
		<div class="adderBack" style="display: none">
			<div class="newMovieInform">
				<div class="newMov">
					<br>
					<span class="boldT">&nbsp;Movie Name:</span>
					<br>
					<input type="text" placeholder="Name" class="newMovName">
					<br>
					<span class="boldT">&nbsp;Length:</span>
					<br>
					&nbsp;&nbsp;Hours:&nbsp;<select class="hours">
						<option value="0">0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select>&nbsp;&nbsp;
					Minutes:&nbsp;<select class="minutes">
						<option value="00">00</option>
						<option value="01">01</option>
						<option value="02">02</option>
						<option value="03">03</option>
						<option value="04">04</option>
						<option value="05">05</option>
						<option value="06">06</option>
						<option value="07">07</option>
						<option value="08">08</option>
						<option value="09">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
						<option value="32">32</option>
						<option value="33">33</option>
						<option value="34">34</option>
						<option value="35">35</option>
						<option value="36">36</option>
						<option value="37">37</option>
						<option value="38">38</option>
						<option value="39">39</option>
						<option value="40">40</option>
						<option value="41">41</option>
						<option value="42">42</option>
						<option value="43">43</option>
						<option value="44">44</option>
						<option value="45">45</option>
						<option value="46">46</option>
						<option value="47">47</option>
						<option value="48">48</option>
						<option value="49">49</option>
						<option value="50">50</option>
						<option value="51">51</option>
						<option value="52">52</option>
						<option value="53">53</option>
						<option value="54">54</option>
						<option value="55">55</option>
						<option value="56">56</option>
						<option value="57">57</option>
						<option value="58">58</option>
						<option value="59">59</option>
					</select>
					<br>
					<br>
					<span class="boldT">&nbsp;Movie Image Link:</span>
					<br>
					<input type="text" placeholder="Image Link" class="newImg">
					<br>
					<span class="boldT">&nbsp;Genre:</span>
					<br>
					<div class="genreList">
					</div>
					<input type="radio" value="Other" name="genreSel" checked="checked">Other:&nbsp;&nbsp;<input type="text" width="30" class="newGenre" placeholder="New Genre Name">
					<br><span class="errorTxt"><br></span>
					<input type="button" onclick="addMovie()" class="btn-large waves-effect waves-red buttonRed" value="Add Movie">
					<br><br>
					<span onclick="closeInfo()"><i class="material-icons close">highlight_off</i></span>					
				</div>
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
									<td class="movieInfoTitle" colspan="3">
										
									</td>
								</tr>
								<tr>
									<td class="middle boldT movInfLength">
										
									</td>
									<td class="middle boldT movInfGenre">
										
									</td>
									<td class="middle boldT movInfLast">
										
									</td>
								</tr>
								<tr>	
									<td colspan="3">
										<span class="boldT">Comments:</span>
										<br>
										<span class="commentsText"></span>
										<textArea class="commentsInput">
										</textArea>
									</td>
								</tr>
								<tr>
									<td class="center">
										<input type="button" onclick="editCom()" class="btn-large waves-effect waves-red buttonRed" value="Add/Edit Comment">
									</td>
									<td class="center">
										<input type="button" onclick="watched()" class="btn-large waves-effect waves-red buttonRed" value="Watched">
									</td>
									<td class="center">
										<input type="button" onclick="deleteMov()" class="btn-large waves-effect waves-red buttonRed" value="Delete">
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