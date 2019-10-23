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

//GET (get all tasks) 
if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if (!$dbconnecterror) {
		try {
			$sql = "SELECT * FROM doList";
			$stmt = $dbh->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			http_response_code(200); // successful request
			echo json_encode ($result);
			exit();
			
		} catch (PDOException $e) {
			http_response_code(504); //Gateway Timeout
			echo "database exception maybe fields";
			exit();
		}
	}
} else {
	http_response_code(405); //method not allowed
	echo "expected GET";
	exit();	
}
