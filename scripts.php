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
		case 'checkUser':
			checkUser();
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
		
		////FIX/////
		require 'password.php';
		
		$uName = $_POST['username'];
		$stmt = $db->prepare('SELECT username, password FROM user WHERE username=:uname;');
		$stmt->bindValue(':uname', $uName, PDO::PARAM_STR);
		$stmt->execute();
		$passwordHash = "";
		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$passwordHash = $row['password'];		
		}
		
		$unhashed = $_POST['password'];		
		
		if (password_verify($unhashed, $passwordHash)) {
			$isAuth = true;
			echo "1";
			session_start();
			$_SESSION["isLoggedIn"] = true;
			$uId = "";
			
			//bind prepared statement
			$stmt = $db->prepare('SELECT id FROM user WHERE username=:user;');
			$stmt->bindValue(':user', $uName, PDO::PARAM_STR);
			$stmt->execute();
			
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$uId = $row['id'];
			}
			$_SESSION["userId"] = $uId;
			exit;
		}
		else {
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
		
		$stmt = $db->prepare('SELECT ml.id AS mov_id, m.name AS movie_name, m.img_link, g.name AS genre, m.length, ml.last_watched, ml.description FROM movie_lookup ml INNER JOIN user u ON u.id = ml.user_id INNER JOIN movie m ON ml.movie_id = m.id INNER JOIN genre g ON ml.genre_id = g.id WHERE ml.user_id=:id;');
		$stmt->bindValue(':id', $myId, PDO::PARAM_INT);
		$stmt->execute();
		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
		
		$stmt = $db->prepare('UPDATE movie_lookup SET description=:describe WHERE id=:movId;');
		$stmt->bindValue(':describe', $comm, PDO::PARAM_STR);
		$stmt->bindValue(':movId', $movLid, PDO::PARAM_INT);
		$stmt->execute();
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
		
		$stmt = $db->prepare('UPDATE movie_lookup SET last_watched=:date WHERE id=:movId;');
		$stmt->bindValue(':date', $dateW, PDO::PARAM_STR);
		$stmt->bindValue(':movId', $movLid, PDO::PARAM_INT);
		$stmt->execute();
		
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
		
		$stmt = $db->prepare('INSERT INTO genre(name) VALUES(:genre);');
		$stmt->bindValue(':genre', $genreName, PDO::PARAM_STR);
		$stmt->execute();
		
		$stmt = $db->prepare('INSERT INTO genre_lookup(user_id, genre_id) VALUES(:id, :lastId)');
		$stmt->bindValue(':id', $myId, PDO::PARAM_INT);
		$stmt->bindValue(':lastId', $db->lastInsertId(), PDO::PARAM_INT);
		$stmt->execute();
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
		
		$stmt = $db->prepare('SELECT g.name AS name FROM genre g INNER JOIN genre_lookup gl ON gl.genre_id = g.id WHERE gl.user_id=:id;');
		$stmt->bindValue(':id', $myId, PDO::PARAM_INT);
		$stmt->execute();
		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
		
		$stmt = $db->prepare('SELECT g.id AS id FROM genre_lookup gl INNER JOIN genre g ON gl.genre_id = g.id WHERE g.name=:genre AND gl.user_id=:id;');
		$stmt->bindValue(':genre', $mGenre, PDO::PARAM_STR);
		$stmt->bindValue(':id', $myId, PDO::PARAM_INT);
		$stmt->execute();
		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$mGenreId = $row['id'];
		}
		
		$stmt = $db->prepare('INSERT INTO movie (name, length, img_link) VALUES (:name, :length, :link);');
		$stmt->bindValue(':name', $mName, PDO::PARAM_STR);
		$stmt->bindValue(':length', $mLength, PDO::PARAM_STR);
		$stmt->bindValue(':link', $mLink, PDO::PARAM_STR);
		$stmt->execute();
		
		$stmt = $db->prepare('INSERT INTO movie_lookup (user_id, movie_id, genre_id) VALUES (:id, :lastId, :genreId);');
		$stmt->bindValue(':id', $myId, PDO::PARAM_INT);
		$stmt->bindValue(':lastId', $db->lastInsertId(), PDO::PARAM_INT);
		$stmt->bindValue(':genreId', $mGenreId, PDO::PARAM_INT);
		$stmt->execute();
		
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
		
		$stmt = $db->prepare('DELETE FROM movie_lookup WHERE id=:movid;');
		$stmt->bindValue(':movid', $movId, PDO::PARAM_INT);
		$stmt->execute();		
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
		
		require 'password.php';
		$userName = $_POST['username'];
		$preHash = $_POST['password'];
		$password = password_hash($preHash, PASSWORD_DEFAULT);
		$name = $_POST['name'];
		$email = $_POST['email'];
				
		$stmt = $db->prepare('INSERT INTO user (username, password, name, email) VALUES (:uname, :password, :name, :email);');
		$stmt->bindValue(':uname', $userName, PDO::PARAM_STR);
		$stmt->bindValue(':password', $password, PDO::PARAM_STR);
		$stmt->bindValue(':name', $name, PDO::PARAM_STR);
		$stmt->bindValue(':email', $email, PDO::PARAM_STR);
		$stmt->execute();
		
		$newId = $db->lastInsertId();
		
		$stmt = $db->prepare('INSERT INTO genre_lookup (user_id, genre_id) VALUES (:newId, 1),(:newId, 2),(:newId, 3),(:newId, 4),(:newId, 5),(:newId, 6)');
		$stmt->bindValue(':newId', $newId, PDO::PARAM_INT);
		$stmt->execute();
		
		$_SESSION["isLoggedIn"] = true;
		$_SESSION["userId"] = $newId;
		
		exit;		
	}
	
	function checkUser() {
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
		
		$stmt = $db->prepare('SELECT username FROM user WHERE username=:uname;');
		$stmt->bindValue(':uname', $userName, PDO::PARAM_STR);
		$stmt->execute();
		
		$contains = false;
		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$contains = true;
		}
		
		if ($contains) {
			echo "0";
		}
		else {
			echo "1";
		}
		exit;
	}
?>