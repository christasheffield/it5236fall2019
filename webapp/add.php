<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$listItem = $_POST['listItem'];
	
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

	$url = "http://3.81.195.62/api/task.php";
	$data = json_encode([
		'completed' => $complete,
		'taskName' => $listItem,
		'taskDate' => $finBy
	]);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data)
	]);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response  = curl_exec($ch);
	$http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($http_status_code == 201) {
		header("Location: index.php");
	} else {
		header("Location: index.php?error=add");
	}
}


