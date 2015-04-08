<?php
    $dbName = "";
	$dbUsername = "";
	$dbPassword = "";
	
	$db = new mysqli('localhost', $dbUsername, $dbPassword, $dbName);
	if($db->connect_errno > 0){die('Unable to connect to database [' . $db->connect_error . ']');}
	$db->set_charset("utf8");  
	
	header('Content-Type: text/csv; charset=utf-8'); 
	header('Content-Disposition: attachment; filename=data.csv'); 
	$output = fopen('php://output', 'w'); 
	fputcsv($output, array('id', 'uploadTime', 'uploaderIP', 'reCAPTCHApass', 'imageDate', 'imageTime', 'imagePath', 'EXIFmanufacturer', 'EXIFmodel', 'gpsLat', 'gpsLong', 'gpsAlt', 'gpsTimestamp', 'restaurantName', 'price', 'numWonton', 'rating')); 
	$result = $db->query("SELECT * FROM `upload`");
	while ($row = $result->fetch_array(MYSQLI_NUM)){
		fputcsv($output, $row);
	}
?>