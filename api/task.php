<?php
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

//PUT (Update task)
if ($_SERVER['REQUEST_METHOD'] == "PUT") {
	if (array_key_exists('listID', $_GET)) {
		$listID = $_GET['listID'];
	} else {
		//Bad request
		http_response_code(404);
		echo "Missing list";
		exit();
	}

	// Decoding the json body from the request
	$task = json_decode(file_get_contents('php://input'), true);
	if ($task == NULL){
		http_response_code(400);
		exit();
	}
	
	//Data Validation
	if (array_key_exists('completed', $task)) {
		$complete = $task["completed"];
	} else {
		//Bad request
		http_response_code(404);
		echo "Missing complete";
	exit();
	}
    if (array_key_exists('taskName', $task)) {
		$taskName = $task["taskName"];
	} else {
		//Bad request
		http_response_code(404);
		echo "Missing taskName";
	exit();
	}
	if (array_key_exists('taskDate', $task)) {
		$taskDate = $task["taskDate"];
	} else {
		//Bad request
		http_response_code(404);
		echo "Missing taskDate";
	exit();
	}

if (!$dbconnecterror) {
		try {
			$sql = "UPDATE doList SET complete=:complete, listItem=:listItem, finishDate=:finishDate WHERE listID=:listID";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":complete", $complete);
			$stmt->bindParam(":listItem", $taskName);
			$stmt->bindParam(":finishDate", $taskDate);
			$stmt->bindParam(":listID", $listID);
			$response = $stmt->execute();
			http_response_code(200);
			$task['listID'] = $taskID;
			echo json_encode($task);
			exit();

		} catch (PDOException $e) {
			http_response_code(504); //Gateway Timeout
			echo "database exception maybe fields";
			exit();
		}
		
	} else {
			http_response_code(504); //Gateway Timeout
			echo "database error";
			exit();
	}
//POST (Add a task)
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
	//add new code here for adding task
	//no id needed here, hasn't been created yet
	
	// Decoding the json body from the request
	$task = json_decode(file_get_contents('php://input'), true);
	if ($task == NULL){
		http_response_code(400);
		exit();
	}
	
	//Data Validation
	if (array_key_exists('completed', $task)) {
		$complete = $task["completed"];
	} else {
		//Bad request
		http_response_code(404);
		echo "Missing complete";
	exit();
	}
    if (array_key_exists('taskName', $task)) {
		$taskName = $task["taskName"];
	} else {
		//Bad request
		http_response_code(404);
		echo "Missing taskName";
	exit();
	}
	if (array_key_exists('taskDate', $task)) {
		$taskDate = $task["taskDate"];
	} else {
		//Bad request
		http_response_code(404);
		echo "Missing taskDate";
	exit();
	}

	if (!$dbconnecterror) {
		try {
			$sql = "INSERT INTO doList (listItem, finishDate, complete) VALUES (:listItem, :finishDate, :complete)";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":complete", $complete);
			$stmt->bindParam(":listItem", $taskName);
			$stmt->bindParam(":finishDate", $taskDate);
			$stmt->execute();
			$taskID = $dbh->lastInsertId();
			http_response_code(201); //task created
			$task['listID'] = $taskID;
			echo json_encode($task);
			exit();

		} catch (PDOException $e) {
			http_response_code(504); //Gateway Timeout
			echo "database exception maybe fields";
			exit();
		}
		
	} else {
			http_response_code(504); //Gateway Timeout
			echo "database error";
			exit();
	}
	
	
//DELETE (delete a task)
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
	if (array_key_exists('listID', $_GET)) {
		$listID = $_GET['listID'];
	} else {
		http_response_code(400);
		echo "Missing task ID";
		exit();
	}
	
	if (!$dbconnecterror) {
		try {
			$sql = "DELETE FROM doList WHERE listID=:listID";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":listID", $listID);
			$response = $stmt->execute();
			http_response_code(204); //no content		
			exit();

		} catch (PDOException $e) {
			http_response_code(504); //Gateway Timeout
			echo "database exception maybe fields";
			exit();
		}
		
	} else {
			http_response_code(504); //Gateway Timeout
			echo "database error";
			exit();
	}
	
} else {
	http_response_code(405); //method not allowed
	echo "expected PUT, POST, or DELETE";
	exit();	
	
}

