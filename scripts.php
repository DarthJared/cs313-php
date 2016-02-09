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
		
		$dbHost = getenv('OPENSHIFT_MYSQL_DB_HOST'); 
		$dbPort = getenv('OPENSHIFT_MYSQL_DB_PORT'); 
		$dbUser = getenv('OPENSHIFT_MYSQL_DB_USERNAME'); 
		$dbPassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
		
		$openShiftVar = getenv('OPENSHIFT_MYSQL_DB_HOST'); 

		if ($openShiftVar === null || $openShiftVar == "") {		
			$db = new PDO('mysql:host=127.0.0.1;dbname=movie_manager', $userN, $password);
		}
		else {
			$db = new PDO('mysql:host=' . $dbHost . ':' . $dbPort . ';dbname=movie_manager', $dbUser, $dbPassword);
		}
		
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
		
		$dbHost = getenv('OPENSHIFT_MYSQL_DB_HOST'); 
		$dbPort = getenv('OPENSHIFT_MYSQL_DB_PORT'); 
		$dbUser = getenv('OPENSHIFT_MYSQL_DB_USERNAME'); 
		$dbPassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
		if ($openShiftVar === null || $openShiftVar == "") {		
			$db = new PDO('mysql:host=127.0.0.1;dbname=movie_manager', $userN, $password);
		}
		else {
			$db = new PDO('mysql:host=' . $dbHost . ':' . $dbPort . ';dbname=movie_manager', $dbUser, $dbPassword);
		}
		
		$toJson = array();
		$jsonStr = "{\"movies\" : [ "; 
		$count = 0;
		foreach ($db->query('SELECT m.id AS mov_id, m.name AS movie_name, m.img_link, g.name AS genre, m.length, ml.last_watched, ml.description FROM movie_lookup ml INNER JOIN user u ON u.id = ml.user_id INNER JOIN movie m ON ml.movie_id = m.id INNER JOIN genre g ON ml.genre_id = g.id WHERE ml.user_id = ' . $myId . ' ORDER BY m.name ASC;') as $row) {
			$mName = $row['movie_name'];
			$link = $row['img_link'];
			$genre = $row['genre'];
			$length = $row['length'];
			$watched = $row['last_watched'];
			$description = $row['description'];
			$id = $row['mov_id'];
			
			if ($count == 0) {
				$jsonStr .= "{ \"name\":\"" . $mName . "\" , \"link\" : \"" . $link . "\" , \"genre\" : \"" . $genre . "\" , \"length\" : \"" . $length . "\" , \"last\" : \"" . $watched . "\" , \"description\" : \"" . $description . "\" , \"id\" : \"" . $id . "\" }";
			}
			else {
				$jsonStr .= ",{ \"name\":\"" . $mName . "\" , \"link\" : \"" . $link . "\" , \"genre\" : \"" . $genre . "\" , \"length\" : \"" . $length . "\" , \"last\" : \"" . $watched . "\" , \"description\" : \"" . $description . "\" , \"id\" : \"" . $id . "\" }";
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
		
		$dbHost = getenv('OPENSHIFT_MYSQL_DB_HOST'); 
		$dbPort = getenv('OPENSHIFT_MYSQL_DB_PORT'); 
		$dbUser = getenv('OPENSHIFT_MYSQL_DB_USERNAME'); 
		$dbPassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
		if ($openShiftVar === null || $openShiftVar == "") {		
			$db = new PDO('mysql:host=127.0.0.1;dbname=movie_manager', $userN, $password);
		}
		else {
			$db = new PDO('mysql:host=' . $dbHost . ':' . $dbPort . ';dbname=movie_manager', $dbUser, $dbPassword);
		}
		
		$toJson = array();
		$jsonStr = "{\"movies\" : [ "; 
		$count = 0;
		foreach ($db->query('SELECT m.id AS mov_id, m.name AS movie_name, m.img_link, g.name AS genre, m.length, ml.last_watched, ml.description FROM (movie_lookup ml INNER JOIN user u ON u.id = ml.user_id INNER JOIN movie m ON ml.movie_id = m.id INNER JOIN genre g ON ml.genre_id = g.id) WHERE ml.user_id <> ' . $myId . ' ORDER BY m.name ASC;') as $row) {
			$mName = $row['movie_name'];
			$link = $row['img_link'];
			$genre = $row['genre'];
			$length = $row['length'];
			$watched = $row['last_watched'];
			$description = $row['description'];
			$id = $row['mov_id'];
			
			if ($count == 0) {
				$jsonStr .= "{ \"name\":\"" . $mName . "\" , \"link\" : \"" . $link . "\" , \"genre\" : \"" . $genre . "\" , \"length\" : \"" . $length . "\" , \"last\" : \"" . $watched . "\" , \"description\" : \"" . $description . "\" , \"id\" : \"" . $id . "\" }";
			}
			else {
				$jsonStr .= ",{ \"name\":\"" . $mName . "\" , \"link\" : \"" . $link . "\" , \"genre\" : \"" . $genre . "\" , \"length\" : \"" . $length . "\" , \"last\" : \"" . $watched . "\" , \"description\" : \"" . $description . "\" , \"id\" : \"" . $id . "\" }";
			}		
			
			$count++;
		}
		$jsonStr .= " ]}";
		echo $jsonStr;
		exit;
	}
	
?>