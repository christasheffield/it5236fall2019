<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);

require 'vendor/autoload.php';
require 'credentials.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

use Aws\DynamoDb\DynamoDbClient;

// Create a DynamoDB client
$dynamodb = DynamoDbClient::factory(array(
	'credentials' => array(
		'key' => $access_key,
		'secret' => $secret_key
    	),
	'region' => $region,
	'version' => 'latest'
));

$marshaler = new Marshaler();

$params = [
    'TableName' => 'tasks',
    'ProjectionExpression' => 'listID, taskName, taskDate, completed'
];

$error_message = "";
$all_tasks = array();

try {
    while (true) {
        $result = $dynamodb->scan($params);

	foreach ($result['Items'] as $i) {
            $tasks = $marshaler->unmarshalItem($i);
			array_push($all_tasks, $tasks);
        }

        if (isset($result['LastEvaluatedKey'])) {
            $params['ExclusiveStartKey'] = $result['LastEvaluatedKey'];
        } else {
            break;
        }
    }

} catch (DynamoDbException $e) {
    $error_message = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta name="description" content="doIT">
	<meta name="author" content="Russell Thackston">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>doIT</title>

	<link rel="stylesheet" href="style/style.css">
	
	<link href="https://fonts.googleapis.com/css?family=Chilanka%7COrbitron&display=swap" rel="stylesheet">		

</head>


<body>
	<a href="index.php"><h1 id="siteName">doIT</h1></a>
	<hr>
	<?php foreach($all_tasks as $task) { ?>
		<div class="list">
			<input type="text" disabled name="listID" value="<?php echo $task["listID"];?>" >
			<input type="text" 	name="listItem" size="50" value="<?php echo $task["taskName"];?>" maxlength="100" >
			<span>by:</span>
			<input type="date" 	name="finBy" value="<?php if($task['taskDate']){echo $task['taskDate'];} ?>" >
			<input type="checkbox" disabled name="fin" <?php if($task["completed"]){echo "checked='checked'";} ?> >
		</div>
	<?php } ?>
</body>
</html>
