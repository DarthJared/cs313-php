<!DOCTYPE html>
<html>
	<head>
		<title>Awesome Survey</title>
		<link rel="stylesheet" type="text/css" href="survey.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>
			var redirect = false;
<?php
	if(!isset($_COOKIE["has_visited_survey"])) {
		echo "redirect = true;";
	}
?>

			if (redirect) {
				window.location.replace("http://php-jbeagley.rhcloud.com/results.php");	
			}
		</script>
	</head>
	<body>
		<div id="titleText">
			Computer Survey
		</div>
		<div id="surveyForm">
			
			<form action="submitResults.php" method="post">
				<a href="results.php">Go to results</a><br>
				<span>Please fill out all of the information honestly...</span>
				<span><p>Which of the following brand of computer do you primarily use?</p></span>
				<input type="radio" name="brand" value="HP"><span>HP</span><br>
				<input type="radio" name="brand" value="Dell"><span>Dell</span><br>
				<input type="radio" name="brand" value="Toshiba"><span>Toshiba</span><br>
				<input type="radio" name="brand" value="Lenovo"><span>Lenovo</span><br>
				<input type="radio" name="brand" value="Asus"><span>Asus</span><br>
				<input type="radio" name="brand" value="Acer"><span>Acer</span><br>
				<input type="radio" name="brand" value="Apple"><span>Mac</span><br>
				<input type="radio" name="brand" value="Other"><span>Other</span><br>
				<p>Which of the following operation systems do you primarily use?</p>
				<input type="radio" name="os" value="Windows"><span>Windows</span><br>
				<input type="radio" name="os" value="OSX"><span>OSX</span><br>
				<input type="radio" name="os" value="Linux"><span>Linux</span><br>
				<input type="radio" name="os" value="Other"><span>Other</span><br>
				<p>How much time do you spend on your computer a week?</p>
				<input type="radio" name="time" value="0-10"><span>0-10 Hours</span><br>
				<input type="radio" name="time" value="10-20"><span>10-20 Hours</span><br>
				<input type="radio" name="time" value="20-40"><span>20-40 Hours</span><br>
				<input type="radio" name="time" value="More than 40"><span>More than 40 Hours</span><br>
				<p>Do you use a laptop or desktop primarily?</p>
				<input type="radio" name="type" value="Laptop"><span>Laptop</span><br>
				<input type="radio" name="type" value="Desktop"><span>Desktop</span><br><br>
				<button type="submit">Submit</button>
			</form>
		</div>
	</body>
</html>