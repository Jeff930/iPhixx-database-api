<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';
require_once '../../include/DBQuery/QueryPost.php';

if (isset($_SERVER['HTTP_ORIGIN'])) {
    // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
    // whitelist of safe domains
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}



$query = new DBQuery();
$app = new \Slim\App;


//Latest post// Recommended post -- sort post based on previous views // 

	$app->get('/', function (Request $request, Response $response, array $args) use($query) {
		$params = $request->getQueryParams();
		if(count($params)){
		 $result = $query->getCustomersByPage($params);		
		}
		else{	
		$result = $query->getCustomers();	
		
		}
		return json_encode($result);
	});

	$app->post('/sign-in', function (Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->logInAgent($body);	
		return json_encode($result);
	});
	$app->post('/',function(Request $request, Response $response, array $args) use($query){
		$body = $request->getParsedBody();	
		$result = $query->addCustomer($body);
		if($result){
			return json_encode($result);
		}
		else{
			return null;
		}
	});

	$app->put('/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];
		$body = $request->getParsedBody();	
		$result = $query->updateCustomer($id , $body);	
		return json_encode($body);
	});

	$app->delete('/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];
		$result = $query->deleteCustomer($id);
		if($result){
			return json_encode($result);
		}
		return false;
	});

	$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});

$app->run();