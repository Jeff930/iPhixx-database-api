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
    header('Content-type: text/plain; charset=utf-8');
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
	$app->get('/tax', function (Request $request, Response $response, array $args) use($query) {
	
		 $result = $query->getTax();		
		return json_encode($result);
	});

	$app->get('/get-tax/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		 $result = $query->getOneTax($id);		
		return json_encode($result);
	});	

	$app->put('/edit-tax/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$body = $request->getParsedBody();
		$result = $query->updateTax($id,$body);
		return json_encode($body);
	});

	$app->post('/add-tax',function(Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addTax($body);	
		if($result){
			return json_encode($body);
		}
		
	});	

	$app->get('/phone', function (Request $request, Response $response, array $args) use($query) {
	
		$result = $query->getModels();		
		return json_encode($result);
	});

	$app->get('/tablet', function (Request $request, Response $response, array $args) use($query) {
	
		 $result = $query->getTabletModels();		
		return json_encode($result);
	});

	$app->get('/invoices/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		if(count($params)){
		 $result = $query->getInvoicesByPage($params);		
		}
		else{	
	
		$result = $query->getInvoices();	
		}
		return json_encode($result);
	});

	$app->get('/devices/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		if(count($params)){
		 $result = $query->getAllModelsByPage($params);		
		}
		else{	
	
		$result = $query->getAllModels();	
		}
		return json_encode($result);
	});

	$app->get('/invoicescount/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		$result = $query->getInvoicesCount();	
		return json_encode($result);
	});

	$app->get('/inventorycount/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		$result = $query->getInventoryCount();	
		return json_encode($result);
	});

	$app->get('/ticketcount/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		$result = $query->getTicketsCount();	
		return json_encode($result);
	});


	$app->get('/tickets/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		if(count($params)){
		 $result = $query->getTicketsByPage($params);		
		}
		else{	
	
		$result = $query->getTickets();	
		}
		return json_encode($result);
	});

	$app->get('/inventory/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		if(count($params)){
		 $result = $query->getInventoryByPage($params);		
		}
		else{	
	
		$result = $query->getInventory();	
		}
		return json_encode($result);
	});

	$app->get('/owner/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->getOwner($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/repair/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->getRepair($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/check-lead-status/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->checkLeadStatus($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/tablet-list/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		$result = $query->getTabletList($params);			
		return json_encode($result);
	});

	$app->get('/check-repair-status/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->checkRepairStatus($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/check-ticket-status/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->checkTicketStatus($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/check-invoice-status/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->checkInvoiceStatus($id);
		if($result){
			return json_encode($result);
		}
	});


	$app->post('/',function(Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addBooking($body);	
		if($result){
			return json_encode($result);
		}
	});

	$app->post('/models/',function(Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->getModels($body);	
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/status/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->updateBookingStatus($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/update-ticket-status/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->updateTicketStatus($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/update-repair-status/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->updateRepairStatus($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/update-invoice-status/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->updateInvoiceStatus($id);
		if($result){
			return json_encode($result);
		}
	});


	$app->put('/edit-owner/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$body = $request->getParsedBody();
		$result = $query->editOwner($id,$body);
		return json_encode($body);
	});


	$app->put('/lead-lost/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->leadLost($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/cancel-Ticket/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->cancelTicket($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/transfer-lead/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->transferLead($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/start-repair/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->startRepair($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];
		$body = $request->getParsedBody();	
		$result = $query->updateBooking($id , $body);	
		return json_encode($body);
	});

	$app->put('/payment/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->updatePaymentStatus($id);
		if($result){
			return json_encode($result);
		}
	});
	// $app->put('/send/{id}',function(Request $request, Response $response, array $args) use($query){
	// 	$id = $args['id'];	
	// 	$result = $query->sendMail($id);
	// 	if($result){
	// 		return json_encode($body);
	// 	}
	// });

	$app->delete('/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];
		$result = $query->deleteBooking($id);
		if($result){
			return json_encode($result);
		}
		return false;
	});

	$app->delete('/delete-tax/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];
		$result = $query->deleteTax($id);
		if($result){
			return json_encode($result);
		}
		return false;
	});

$app->run();

