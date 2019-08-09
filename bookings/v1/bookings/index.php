<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';
require_once '../../include/DBQuery/QueryPost.php';


// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
    // whitelist of safe domains
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}



$query = new DBQuery();
$app = new \Slim\App;



//Latest post// Recommended post -- sort post based on previous views // 

	$app->get('/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		if(count($params)){
		 $result = $query->getBookingsByPage($params);		
		}
		else{	
	
		$result = $query->getBookings();	
		}
		return json_encode($result);
	});

	$app->post('/',function(Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addBooking($body);	
		if($result){
			return json_encode($body);
		}
		
	});

	$app->put('/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->updateBooking($id);
		if($result){
			return json_encode($body);
		}
	});

	$app->delete('/',function(Request $request, Response $response, array $args){
		$body = $request->getParsedBody();
		return json_encode($body);
	});

$app->run();

