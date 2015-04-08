<?php
	$dbName = "";
	$dbUsername = "";
	$dbPassword = "";
	$reCAPTCHA_key = "";

	$requestIP = $_SERVER['REMOTE_ADDR']; 
	$time = $_POST["time"]; 
	$date = $_POST["date"]; 
	$reCAPTCHA = $_POST["reCAPTCHAres"]; 
	$image = $_POST['imageURL']; 
	$restaurantName = $_POST['restaurantName'];
	$price = $_POST['price'];
	$numWonton = $_POST['numWonton'];
	$rating = $_POST['rating'];
	$EXIF = json_decode($_POST['EXIF']);
	$EXIFmanufacturer = $EXIF->manufacturer;
	$EXIFmodel = $EXIF->model;
	if ($EXIF->gps !== NULL){
		$EXIFlat = $EXIF->gps->latitudeRef." ".$EXIF->gps->latitude[0]."º ".$EXIF->gps->latitude[1]."' ".$EXIF->gps->latitude[2].'"';
		$EXIFlong = $EXIF->gps->longitudeRef." ".$EXIF->gps->longitude[0]."º ".$EXIF->gps->longitude[1]."' ".$EXIF->gps->longitude[2].'"';
		$EXIFalt = $EXIF->gps->altitudeRef.$EXIF->gps->altitude; 
		$EXIFtimestamp = $EXIF->gps->timeStamp[0].":".$EXIF->gps->timeStamp[1].":".$EXIF->gps->timeStamp[2];
	}
	
	$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$reCAPTCHA_key."&response=".$reCAPTCHA."&remoteip=".$requestIP);
	if (strpos($response, 'true') !== false){$reCAPTCHApass = 1;}else{$reCAPTCHApass = 0;}
	
	$image = explode(",", $image);
	$imageExt = explode("/", $image[0]);
	$imageExt = explode(";", $imageExt[1]);
	$imageExt = ".".$imageExt[0];
	if ($imageExt == ".jpeg"){$imageExt == ".jpg";}
	$imageName = hash("sha256", ($time.$date.$requestIP)).$imageExt;
	$path = "../data/".$imageName;
	$image = $image[1]; 
	$image = str_replace(" ", "+", $image);
	file_put_contents($path, base64_decode($image));

	$db = new mysqli('localhost', $dbUsername, $dbPassword, $dbName);
	if($db->connect_errno > 0){die('Unable to connect to database [' . $db->connect_error . ']');}
	$db->set_charset("utf8");  
	$statement = $db->prepare("INSERT INTO `upload` (`uploaderIP`, `reCAPTCHApass`, `imageDate`, `imageTime`, `imagePath`, `EXIFmanufacturer`, `EXIFmodel`, `gpsLat`, `gpsLong`, `gpsAlt`, `gpsTimestamp`, `restaurantName`, `price`, `numWonton`, `rating`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"); 
	if ($statement === false){die('prepare() failed: ' . htmlspecialchars($db->error));}
	$statement->bind_param("sisssssssdssdii", $requestIP, $reCAPTCHApass, $date, $time, $path, $EXIFmanufacturer, $EXIFmodel, $EXIFlat, $EXIFlong, $EXIFalt, $EXIFtimestamp, $restaurantName, $price, $numWonton, $rating); 
	$statement->execute(); 
?>