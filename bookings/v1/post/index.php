<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

require_once '../../include/DBOps/DBConnection.php';
require_once '../../include/DBQuery/QueryPost.php';

$conn= new DBConnection();
$db = $conn->mConnect();
$app = new \Slim\App;
$query = new DBQuery();

//Latest post// Recommended post -- sort post based on previous views // 
	$app->get('/recent', function (Request $request, Response $response, array $args) use($db){
		$response = $query->getCustomers();

		return json_encode($response);
	});
	$app->get('/customers', function (Request $request, Response $response, array $args) {
		$response = $query->getCustomers();

		return json_encode($response);
	});
	$app->post('/',function(Request $request, Response $response, array $args){
		$body = $request->getParsedBody();
		return json_encode($body);
	});

	$app->put('/',function(Request $request, Response $response, array $args){

		$body = $request->getParsedBody();
		return json_encode($body);
	});

	$app->delete('/',function(Request $request, Response $response, array $args){
		$body = $request->getParsedBody();
		return json_encode($body);
	});

$app->run();