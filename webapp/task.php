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
	//database issues were encountered
	http_response_code(504);
	echo "database issues were encountered";
	exit();
	}

	if ($_SERVER['REQUEST_METHOD'] == "PUT") {
		if (array_key_exists['listID'];
	} else {
		http_response_code(400);
		echo "bad request";
		exit();
	}

$task = json_decode(file_get_contents('php://input'), true);

if (array_key_+exists('completed', $task)) {
	$complete = $task["completed"];
	if($complete == TRUE){
		$completed=1;
	} else {
		$completed=0;
	}

	} else { //couldn't find completed
		   http_response_code(422);
		   echo "bad request for completion";
		   exit();
	}
	
	if (array_key_exists('taskName', $task)) {
		$taskName = $task["taskName"];
	} else {
		http_response_code(422);
		echo "bad request for task name";
		exit();
	}

	if (array_key_exists('taskDate', $task)) {
		$taskDate = $task["taskDate];
	} else {
		http_response_code(422);
		echo "bad request for date";
		exit();
	}

//add two fields here
	if (!$dbconnecterror) {
		try {
			$sql = "UPDATE doList SET complete=:complete, listItem=:listItem, finishDate=:finishDate, listID=:listID)";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":complete", $complete);
			$stmt->bindParam(":listItem", $listItem);
			$stmt->bindParam(":finishDate", $finishDate);
			$stmt->bindParam(":listID", $listID);
			$response = $stmt->execute();
			http_response_code(204);
			echo "ok";
			exit();

		} 	catch (PDOException $e) {
				http_response_code(504); //Gateway Timeout
				echo "database error";
				exit();
			}
		} else {
			http_response_code(504)://Gateway tiomeout
			echo "gateway timeout";
			exit();
			}
		} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
			//http_response_code(405); //method not allowed
			//echo "gateway timeout";
				












