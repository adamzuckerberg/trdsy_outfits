<?php
require(__dir__."/../inc/config.php");

// Save the image
	define('UPLOAD_DIR', '../images/items/');
	$image = $_FILES['primary_image'];
	error_log($image['tmp_name']);
	$fileName = uniqid() . '.jpg';
	$file = UPLOAD_DIR . $fileName;
	move_uploaded_file($image["tmp_name"], $file);
	// $success = file_put_contents($file, $data);
	// print $success ? $file : 'Unable to save the file.';
	// echo JSON_encode(array('imageName'=>$fileName));

// Form post data
$primary_name = $_POST['primary_name'];
$primary_price = $_POST['primary_price'];
$primary_color = $_POST['primary_color'];
$primary_used = $_POST['primary_condition'];
$primary_image = $fileName;

try {
	$results = $connection->prepare("INSERT INTO items (name,price,color,used,image) VALUES (?,?,?,?,?)");
	$results->bindParam(1,$primary_name);
	$results->bindParam(2,$primary_price);
	$results->bindParam(3,$primary_color);
	$results->bindParam(4,$primary_used);
	$results->bindParam(5,$primary_image);	
	$results->execute();
}  catch (Exception $e) {
	echo "Data could not be inserted into database.";
   	echo "Failed: " . $e->getMessage();
	exit;
}

try {
	$new_item = $connection->query("SELECT id, name, price, color, used, image FROM items ORDER BY id DESC LIMIT 1");
}  catch (Exception $e) {
	echo "Data could not be retrieved from database.";
   	echo "Failed: " . $e->getMessage();
	exit;
}

$array_new_item = $new_item->fetch(PDO::FETCH_ASSOC);
header("Content-Type: application/json");
echo json_encode(array('item_id'=>intval($array_new_item['id']),'item_name'=>$array_new_item['name'],'item_price'=>$array_new_item['price'],'item_color'=>$array_new_item['color'],'item_used'=>$array_new_item['used'],'image'=>'images/items/'.$array_new_item['image']));

?> 
