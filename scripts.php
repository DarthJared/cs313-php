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
		case 'addComment':
			addComment();
			break;
		case 'watch':
			watch();
			break;
		case 'addGenre':
			addGenre();
			break;
		case 'getGenre':
			getGenre();
			break;
		case 'addMovie':
			addMovie();
			break;
		case 'delete':
			delete();
			break;
		case 'addUser':
			createAccount();
			break;
		case 'getUsernames':
			getUsers();
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
		
		$openShiftVar = getenv('OPENSHIFT_MYSQL_DB_HOST'); 

		if ($openShiftVar === null || $openShiftVar == "") {		
			$db = new PDO('mysql:host=127.0.0.1;dbname=movie_manager', $userN, $password);
		}
		else {
			$db = new PDO('mysql:host=' . $dbHost . ':' . $dbPort . ';dbname=movie_manager', $dbUser, $dbPassword);
		}
		
		$toJson = array();
		$jsonStr = "{\"movies\" : [ "; 
		$count = 0;
		foreach ($db->query('SELECT ml.id AS mov_id, m.name AS movie_name, m.img_link, g.name AS genre, m.length, ml.last_watched, ml.description FROM movie_lookup ml INNER JOIN user u ON u.id = ml.user_id INNER JOIN movie m ON ml.movie_id = m.id INNER JOIN genre g ON ml.genre_id = g.id WHERE ml.user_id = ' . $myId . ' ORDER BY m.name ASC;') as $row) {
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
		
		$openShiftVar = getenv('OPENSHIFT_MYSQL_DB_HOST'); 

		if ($openShiftVar === null || $openShiftVar == "") {		
			$db = new PDO('mysql:host=127.0.0.1;dbname=movie_manager', $userN, $password);
		}
		else {
			$db = new PDO('mysql:host=' . $dbHost . ':' . $dbPort . ';dbname=movie_manager', $dbUser, $dbPassword);
		}
		
		$toJson = array();
		$jsonStr = "{\"movies\" : [ "; 
		$count = 0;
		foreach ($db->query('SELECT ml.id AS mov_id, m.name AS movie_name, m.img_link, g.name AS genre, m.length, ml.last_watched, ml.description, ml.user_id AS user_id FROM (movie_lookup ml INNER JOIN user u ON u.id = ml.user_id INNER JOIN movie m ON ml.movie_id = m.id INNER JOIN genre g ON ml.genre_id = g.id) GROUP BY m.name HAVING COUNT(m.id) = 1') as $row) {
			if ($row['user_id'] != $myId) {
				$mName = $row['movie_name'];
				$link = $row['img_link'];
				$genre = $row['genre'];
				$length = $row['length'];
				$watched = $row['last_watched'];
				$description = $row['description'];
				//echo $description;
				$id = $row['mov_id'];
				
				if ($count == 0) {
					$jsonStr .= "{ \"name\":\"" . $mName . "\" , \"link\" : \"" . $link . "\" , \"genre\" : \"" . $genre . "\" , \"length\" : \"" . $length . "\" , \"last\" : \"" . $watched . "\" , \"description\" : \"" . $description . "\" , \"id\" : \"" . $id . "\" }";
				}
				else {
					$jsonStr .= ",{ \"name\":\"" . $mName . "\" , \"link\" : \"" . $link . "\" , \"genre\" : \"" . $genre . "\" , \"length\" : \"" . $length . "\" , \"last\" : \"" . $watched . "\" , \"description\" : \"" . $description . "\" , \"id\" : \"" . $id . "\" }";
				}		
				
				$count++;
			}
		}
		$jsonStr .= " ]}";
		echo $jsonStr;
		exit;
	}
	
	function addComment() {
		session_start();
		$myId = $_SESSION["userId"];
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
		
		$movLid = $_POST['movieId'];
		$comm = $_POST['comment'];
		$db->query('UPDATE movie_lookup SET description=\'' . $comm . '\' WHERE id=' . $movLid . ';' );
		exit;
	}
	
	function watch() {
		session_start();
		$myId = $_SESSION["userId"];
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
		$movLid = $_POST['movieId'];
		$dateW = $_POST['date'];
		$db->query('UPDATE movie_lookup SET last_watched=\'' . $dateW . '\' WHERE id=' . $movLid . ';' );
		exit;
	}
	
	function addGenre() {
		session_start();
		$genreName = $_POST['genreName'];
		$myId = $_SESSION["userId"];
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
		
		$db->query('INSERT INTO genre(name) VALUES(\'' . $genreName . '\');' );
		$db->query('INSERT INTO genre_lookup(user_id, genre_id) VALUES(' . $myId . ', ' . $db->lastInsertId() . ');' );
		//echo $genreName;
		exit;
	}
	function getGenre() {
		session_start();
		$myId = $_SESSION["userId"];
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
		$genres = "{\"genres\" : [ ";;
		$count = 0;
		foreach ($db->query('SELECT g.name AS name FROM genre g INNER JOIN genre_lookup gl ON gl.genre_id = g.id WHERE gl.user_id = ' . $myId . ';') as $row) {
			if ($count == 0) {
				$genres .= "{\"name\": \"" . $row['name'] . "\"}";
				$count++;
			}
			else {
				$genres .= ",{\"name\": \"" . $row['name'] . "\"}";
				$count++;
			}
		}
		$genres .= " ]}";
		echo $genres;
		exit;
	}
	
	function addMovie() {
		session_start();
		$myId = $_SESSION["userId"];
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
		
		$mName = $_POST['name'];
		$mGenre = $_POST['genreName'];
		$mLength = $_POST['length'];
		$mLink = $_POST['link'];
		$mGenreId;
		
		foreach($db->query('SELECT g.id AS id FROM genre_lookup gl INNER JOIN genre g ON gl.genre_id = g.id WHERE g.name = \'' . $mGenre . '\' AND gl.user_id = ' . $myId . ';') as $row) {
			$mGenreId = $row['id'];
		}
		
		$db->query('INSERT INTO movie (name, length, img_link) VALUES (\'' . $mName . '\', \'' . $mLength . '\', \'' . $mLink . '\');');
		$db->query('INSERT INTO movie_lookup (user_id, movie_id, genre_id) VALUES (\'' . $myId . '\', \'' . $db->lastInsertId() . '\', \'' . $mGenreId . '\');');
		
		exit;
	}
	
	function delete() {
		session_start();
		$myId = $_SESSION["userId"];
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
		$movId = $_POST['movieId'];
		//echo $movId . " " . $myId;
		//echo 'DELETE FROM movie_lookup WHERE movie_id = ' . $movId . ' AND user_id = ' . $myId . ';';
		
		$db->query('DELETE FROM movie_lookup WHERE id = ' . $movId . ';');
		
		exit;
	}
	
	function createAccount() {
		session_start();
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
		
		$userName = $_POST['username'];
		$password = $_POST['password'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		
		$db->query('INSERT INTO user (username, password, name, email) VALUES (\'' . $userName . '\', \'' . $password . '\', \'' . $name . '\', \'' . $email . '\')');
		
		$newId = $db->lastInsertId();
		
		$db->query('INSERT INTO genre_lookup (user_id, genre_id) VALUES (' . $newId . ', 1),(' . $newId . ', 2),(' . $newId . ', 3),(' . $newId . ', 4),(' . $newId . ', 5),(' . $newId . ', 6)');
		
		$_SESSION["isLoggedIn"] = true;
		$_SESSION["userId"] = $newId;
		
		exit;		
	}
	
	function getUsers() {
		session_start();
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
		
		$count = 0;
		$users = "{\"users\": [";
		foreach($db->query('SELECT username FROM user;') as $row) {
			if($count == 0) {
				$users .= '{"username":"' . $row['username'] . '"}';
				$count++;
			}
			else {
				$users .= ',{"username":"' . $row['username'] . '"}';
				$count++;
			}
		}
		$users .= "]}";
		
		echo $users;
		exit;
	}
?>