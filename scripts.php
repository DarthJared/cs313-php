<?php 
	$action = $_POST['action'];	
	
	switch ($action) {
		case 'login':
			checkUserName();
			break;
		case 'logout':
			logout();
			break;
		case 'myMovies':
			getMyMovies();
			break;
		case 'notMyMovies':
			notMyMovies();
			break;
	}
	
	
	function checkUserName() {
		$userN = 'php';
		$password = 'php-pass';
		$db = new PDO('mysql:host=127.0.0.1;dbname=movie_manager', $userN, $password);
		
		foreach ($db->query('SELECT username, password FROM user') as $row) {
			$user["'" . $row['username'] . "'"] = "'" . $row['password'] . "'";
		    //echo $row['username'] . " " . $row['password'];
		
		}
		
		//echo $user;
		
		$uName = $_POST['username'];
		try {
			$userNa = "";
			if (isset($user["'" . $uName . "'"]))
			{
				$userNa = $user["'" . $uName . "'"];
			}
			else {
				echo "0";
				exit;
			}
			$pass = "'" . $_POST['password'] . "'";
			//echo $userNa;
			//echo $uName;
			//echo $pass;
			$isAuth = false;
			if ($userNa == $pass) {
				$isAuth = true;
				echo "1";
				session_start();
				$_SESSION["isLoggedIn"] = true;
				$uId = "";
				foreach ($db->query('SELECT id FROM user WHERE username = \'' . $uName . '\';') as $row) {
					$uId = $row['id'];
				}
				$_SESSION["userId"] = $uId;
			}
			else {
				echo "0";
			}		
			exit; 
		}
		catch (Exception $e) {
			echo "0";
			exit;
		}
	}
	
	function logout() {
		session_start();
		$_SESSION["isLoggedIn"] = false;
	}
	
	function getMyMovies() {
		session_start();
		$myId = $_SESSION["userId"];
		$userN = 'php';
		$password = 'php-pass';
		$db = new PDO('mysql:host=127.0.0.1;dbname=movie_manager', $userN, $password);
		
		$toJson = array();
		$jsonStr = "{\"movies\" : [ "; 
		$count = 0;
		foreach ($db->query('SELECT m.name AS movie_name, m.img_link, g.name AS genre, m.length, ml.last_watched, ml.description FROM movie_lookup ml INNER JOIN user u ON u.id = ml.user_id INNER JOIN movie m ON ml.movie_id = m.id INNER JOIN genre g ON ml.genre_id = g.id WHERE ml.user_id = ' . $myId . ' ORDER BY m.name ASC;') as $row) {
			$mName = $row['movie_name'];
			$link = $row['img_link'];
			$genre = $row['genre'];
			$length = $row['length'];
			$watched = $row['last_watched'];
			$description = $row['description'];
			
			if ($count == 0) {
				$jsonStr .= "{ \"name\":\"" . $mName . "\" , \"link\" : \"" . $link . "\" , \"genre\" : \"" . $genre . "\" , \"length\" : \"" . $length . "\" , \"last\" : \"" . $watched . "\" , \"description\" : \"" . $description . "\" }";
			}
			else {
				$jsonStr .= ",{ \"name\":\"" . $mName . "\" , \"link\" : \"" . $link . "\" , \"genre\" : \"" . $genre . "\" , \"length\" : \"" . $length . "\" , \"last\" : \"" . $watched . "\" , \"description\" : \"" . $description . "\" }";
			}		
			
			$count++;
		}
		$jsonStr .= " ]}";
		echo $jsonStr;
		exit;
	}
	
	function notMyMovies() {
		session_start();
		$myId = $_SESSION["userId"];
		$userN = 'php';
		$password = 'php-pass';
		$db = new PDO('mysql:host=127.0.0.1;dbname=movie_manager', $userN, $password);
		
		$toJson = array();
		$jsonStr = "{\"movies\" : [ "; 
		$count = 0;
		foreach ($db->query('SELECT m.name AS movie_name, m.img_link, g.name AS genre, m.length, ml.last_watched, ml.description FROM (movie_lookup ml INNER JOIN user u ON u.id = ml.user_id INNER JOIN movie m ON ml.movie_id = m.id INNER JOIN genre g ON ml.genre_id = g.id) WHERE ml.user_id <> ' . $myId . ' ORDER BY m.name ASC;') as $row) {
			$mName = $row['movie_name'];
			$link = $row['img_link'];
			$genre = $row['genre'];
			$length = $row['length'];
			$watched = $row['last_watched'];
			$description = $row['description'];
			
			if ($count == 0) {
				$jsonStr .= "{ \"name\":\"" . $mName . "\" , \"link\" : \"" . $link . "\" , \"genre\" : \"" . $genre . "\" , \"length\" : \"" . $length . "\" , \"last\" : \"" . $watched . "\" , \"description\" : \"" . $description . "\" }";
			}
			else {
				$jsonStr .= ",{ \"name\":\"" . $mName . "\" , \"link\" : \"" . $link . "\" , \"genre\" : \"" . $genre . "\" , \"length\" : \"" . $length . "\" , \"last\" : \"" . $watched . "\" , \"description\" : \"" . $description . "\" }";
			}		
			
			$count++;
		}
		$jsonStr .= " ]}";
		echo $jsonStr;
		exit;
	}
	
?>