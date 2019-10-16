<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Declare the credentials to the database
$dbconnecterror = FALSE;
$dbh = NULL;

require_once 'credentials.php';

try{
	
	$conn_string = "mysql:host=".$dbserver.";dbname=".$db;
	$dbh= new PDO($conn_string, $dbusername, $dbpassword);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
}catch(Exception $e){
	$dbconnecterror = TRUE;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	$listID = $_POST['listID'];
	
	if (array_key_exists('fin', $_POST)) {
		$complete = 1;
	} else {
		$complete = 0;
	}
	if (empty($_POST['finBy'])) {
		$finBy = null;
	} else {
		$finBy = $_POST['finBy'];
	}
	$listItem = $_POST['listItem'];
	

	if (!$dbconnecterror) {
		try {
			$sql = "UPDATE doList SET complete=:complete, listItem=:listItem, finishDate=:finishDate WHERE listID=:listID";
			$stmt = $dbh->prepare($sql);			
			$stmt->bindParam(":complete", $complete);
			$stmt->bindParam(":listItem", $listItem);
			$stmt->bindParam(":finishDate", $finBy);
			$stmt->bindParam(":listID", $listID);

			$response = $stmt->execute();	
			
			header("Location: index.php");
			
		} catch (PDOException $e) {
			header("Location: index.php?error=edit");
			
		}	
	} else {
		header("Location: index.php?error=edit");
	}
}
?>
