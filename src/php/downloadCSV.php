<?php
    require 'settings.php';
	
	$db = new mysqli('localhost', $dbUsername, $dbPassword, $dbName);
	if($db->connect_errno > 0){die('Unable to connect to database [' . $db->connect_error . ']');}
	$db->set_charset("utf8");  
	
	header('Content-Type: text/csv; charset=utf-8'); 
	header('Content-Disposition: attachment; filename=data.csv'); 
	$output = fopen('php://output', 'w'); 
	fputcsv($output, array('imageID', 'uploadTime', 'uploaderIP', 'reCAPTCHApass', 'imageDate', 'imageTime', 'imagePath', 'EXIFmanufacturer', 'EXIFmodel', 'gpsLat', 'gpsLong', 'gpsAlt', 'gpsTimestamp', 'restaurantName', 'address', 'price', 'numWonton', 'rating')); 
	$result = $db->query("SELECT `imageID`, `uploadTime`, `uploaderIP`, `reCAPTCHApass`, `imageDate`, `imageTime`, `imagePath`, `EXIFmanufacturer`, `EXIFmodel`, `gpsLat`, `gpsLong`, `gpsAlt`, `gpsTimestamp`, `restaurantName`, `address`, `price`, `numWonton`, `rating` FROM `upload`");
	while ($row = $result->fetch_array(MYSQLI_NUM)){
		fputcsv($output, $row);
	}
?>