<?php
	$currentFile = fopen("results.txt", "r") or die("Unable to open file!");
	$current = fread($currentFile, filesize("results.txt"));
	fclose($currentFile);
	$cut = substr($current, 9);
	$realCut = rtrim($cut, "</results>");
				
	$writeFile = fopen("results.txt", "w") or die("Unable to open file!");
	$submission = '<results>' . $realCut . "><submission><brand>" . $_POST["brand"] . "</brand><os>" . $_POST["os"] . "</os><time>" . $_POST["time"] . "</time><type>" . $_POST["type"] . "</type></submission></results>";
	
	
	fwrite($writeFile, $submission);				
	fclose($writeFile);
	
	copy("results.txt", "check.txt");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Rusults</title>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>
			var hpCount = 0;
			var dellCount = 0;
			var toshibaCount = 0;
			var lenovoCount = 0;
			var asusCount = 0;
			var acerCount = 0;
			var appleCount = 0;
			var otherBrandCount = 0;
			
			var windowsCount = 0;
			var macCount = 0;
			var linuxCount = 0;
			var otherOsCount = 0;
			
			var under10Count = 0;
			var under20Count = 0;
			var under40Count = 0;
			var more40Count = 0;
			
			var lapCount = 0;
			var deskCount = 0;
			
			<?php
				$readFile = fopen("results.txt", "r") or die("Unable to open file!");
				
				echo "var results = '" . fread($readFile, filesize("check.txt")) . "';";
				fclose($readFile);
			?>
			var xmlText = $.parseXML(results);
			var $xml = $( xmlText );
			
			$xml.find( "submission" ).each(function(){
				alert("here");
				var brand = $(this).find("brand").text();
				if (brand == "HP") {
					hpCount++;
				}
				else if (brand == "Dell") {
					dellCount++;
				}
				else if (brand == "Toshiba") {
					toshibaCount++;
				}
				else if (brand == "Lenovo" ) {
					lenovoCount++;
				}
				else if (brand == "Asus") {
					alert("another");
					asusCount++;
				}
				else if (brand == "Acer") {
					acerCount++;
				}
				else if (brand == "Apple") {
					appleCount++;
				}
				else if (brand == "Other") {
					otherBrandCount++;
				}
				
				var os = $(this).find("os").text();
				if (os == "Windows") {
					windowsCount++;
				}
				else if (os == "OSX") {
					macCount++;
				}
				else if (os == "Linux") {
					linuxCount++;
				}
				else if (os == "Other") {
					otherOsCount++;
				}
				
				var time = $(this).find("time").text();
				if (time ==  "0-10") {
					under10Count++;
				}
				else if (time == "10-20") {
					under20Count++;
				}
				else if (time == "20-40") {
					under40Count++;
				}
				else if (time == "More than 40") {
					more40Count++;
				}
				
				var type = $(this).find("type").text();
				if (type == "Laptop") {
					lapCount++;
				}
				else if (type == "Desktop") {
					deskCount++;
				}
			});
				
				google.charts.load('current', {'packages':['corechart']});
				google.charts.setOnLoadCallback(drawChart);
				function drawChart() {

				var data = google.visualization.arrayToDataTable([
				  ['Brand', 'Number'],
				  ['HP',     hpCount],
				  ['Dell',      dellCount],
				  ['Toshiba',  toshibaCount],
				  ['Lenovo', lenovoCount],
				  ['Asus',    asusCount],
				  ['Acer',  acerCount],
				  ['Apple', appleCount],
				  ['Other',   otherBrandCount]
				]);

				var options = {
				  title: 'Brands'
				};

				var chart1 = new google.visualization.PieChart(document.getElementById('brandChart'));

				chart1.draw(data, options);
				
				
				
				
				var data2 = google.visualization.arrayToDataTable([
				  ['OS', 'Number'],
				  ['Windows',     windowsCount],
				  ['Dell',      macCount],
				  ['Toshiba',  linuxCount],
				  ['Lenovo', otherOsCount]
				]);

				var options = {
				  title: 'OS'
				};

				var chart2 = new google.visualization.PieChart(document.getElementById('osChart'));

				chart2.draw(data2, options);
				
				
				
				
				var data3 = google.visualization.arrayToDataTable([
				  ['Time', 'Number'],
				  ['0-10 Hours',     under10Count],
				  ['10-20 Hours',      under20Count],
				  ['20-40 Hours',  under40Count],
				  ['More Than 40 Hours', more40Count]
				]);

				var options = {
				  title: 'Hours Per Week'
				};
				
				var chart3 = new google.visualization.PieChart(document.getElementById('timeChart'));
				chart3.draw(data3, options);
				
				
				var data4 = google.visualization.arrayToDataTable([
				  ['Type', 'Number'],
				  ['Laptop',     lapCount],
				  ['Desktop',      deskCount]
				]);

				var options = {
				  title: 'Machne Type'
				};

				var chart4 = new google.visualization.PieChart(document.getElementById('typeChart'));

				chart4.draw(data4, options);
				}
				
		</script>
	</head>
	<body>
		<div id="brandChart"></div><br><br>
		<div id="osChart"></div>
		<div id="timeChart"></div>
		<div id="typeChart"></div>
	</body>
</html>