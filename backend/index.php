<?php
define("DEBUG", false);
define("STORAGE_ROOT_PATH", "./storage");
define("PARAM_API_KEY", "api_key");
define("PARAM_NAME", "name");
define("PARAM_ACTION", "action");
define("PARAM_VALUE", "value");
define("ACTION_SET", "set");
define("ACTION_GET", "get");
define("ACTION_CUT", "cut");

if (!DEBUG) {
	error_reporting(0);
	set_time_limit(0);
	ini_set("memory_limit", "-1");
}

function addHeader($code) {
	http_response_code($code);
	header("Content-Type: application/json");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET");
	header("Access-Control-Allow-Headers: Content-Type");
}

function renderResponse($code, $pathName, $pathValue) {
	addHeader($code);

	echo json_encode(
		array(
			"result" => $code,
			"$pathName" => $pathValue
		)
	);
	
	exit;
}

function returnResult($body) {
	renderResponse(200, "body", $body);
}

function returnOk() {
	returnResult("OK");
}

function returnError($code, $message) {
	renderResponse($code, "message", $message);
}

function returnError400() {
	returnError(400, "Bad Request");
}

function returnError401() {
	returnError(401, "Unauthorized");
}

function returnError403() {
	returnError(403, "Not Found");
}

function returnError500() {
	returnError(500, "Internal Server Error");
}

function getParam($name) {
	$result = null;

	if (isset($_GET[$name]))
		$result = $_GET[$name];	
	
	return $result;
}

function getApiKeyStoragePath($apiKey) {
	$apiKeyHash = md5($apiKey);
	$storagePath = STORAGE_ROOT_PATH;
	return "$storagePath/$apiKeyHash/";
}

function checkApiKey($apiKey) {
	$path = getApiKeyStoragePath($apiKey);
	return (file_exists($path) && is_dir($path));
}

function setValue($path, $parameter, $value) {
	$fileName = $path . md5($parameter);
	return file_put_contents($fileName, $value);
}

function getValue($path, $parameter, $clearOnRead = false) {
	$fileName = $path . md5($parameter);

	if (file_exists($fileName)) {
		$storageVal = file_get_contents($fileName);
		if ($clearOnRead === true)
			unlink($fileName);
		
		return $storageVal;
	}
	
	return null;
}

$parameters = [
	PARAM_API_KEY => null,
	PARAM_ACTION => null,
	PARAM_NAME => null,
	PARAM_VALUE => null
];

foreach ($parameters as $parameterName => $parameter)
	$parameters[$parameterName] = getParam($parameterName);

if ($parameters[PARAM_API_KEY] === null || $parameters[PARAM_ACTION] === null || $parameters[PARAM_NAME] === null)
	returnError400();

if (!checkApiKey($parameters[PARAM_API_KEY]))
	returnError401();

$path = getApiKeyStoragePath($parameters[PARAM_API_KEY]);

if ($parameters[PARAM_ACTION] == ACTION_SET) {
	if ($parameters[PARAM_VALUE] === null)
		returnError400();
	
	if (setValue($path, $parameters[PARAM_NAME], $parameters[PARAM_VALUE]) === false) 
		returnError500();
	else 
		returnOk();
	
} else if ($parameters[PARAM_ACTION] === ACTION_GET || $parameters[PARAM_ACTION] === ACTION_CUT) {
	$storageValue = getValue($path, $parameters[PARAM_NAME], ($parameters[PARAM_ACTION] === ACTION_CUT));
	
	if ($storageValue === null) 
		returnError403();
	else
		returnResult($storageValue);
		
} else {
	returnError400();
}
?>