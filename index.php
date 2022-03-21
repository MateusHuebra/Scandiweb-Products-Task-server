<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: *');


spl_autoload_register(function ($class_name) {
    require str_replace('\\', '/', $class_name).'.php';
});

//URLs work as site.com/controller/method
$requestUri = explode('?', $_SERVER['REQUEST_URI']);
$requestUri = str_replace('/scandiwebproducts', '', $requestUri); //exclusive for my private server
$requestUri = explode('/', $requestUri[0]);
if(empty($requestUri[1])) {
	$requestUri[1] = 'Home';
}
$className = 'Controller\\'.ucfirst($requestUri[1]);
if(isset($requestUri[2])) {
	$methodName = $requestUri[2];
} else {
	$methodName = 'index';
}

$controllers = array_diff(scandir('Controller'), ['..', '.']);
$controllers = array_map('strtolower', $controllers);

//if Controller or Method don't exist
if(!in_array(strtolower($requestUri[1]).'.php', $controllers) || !in_array($methodName, get_class_methods($className))) {
    $className = 'Controller\\PageNotFound';
    $methodName = 'index';
}
$controller = new $className();

try {
    $controller->$methodName();
} catch(Exception $e) {
    http_response_code(400);
    echo $e->getMessage();
}