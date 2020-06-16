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

	$app->get('/locations/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		if(count($params)){
		 $result = $query->getLocationsByPage($params);		
		}
		else{	
	
		$result = $query->getLocations();	
		}
		return json_encode($result);
	});

	$app->get('/list-locations', function (Request $request, Response $response, array $args) use($query) {
		$result = $query->getLocationList();		
		return json_encode($result);
	});

	$app->get('/list-brands', function (Request $request, Response $response, array $args) use($query) {
		$result = $query->getBrandList();		
		return json_encode($result);
	});

	$app->get('/list-devtypes', function (Request $request, Response $response, array $args) use($query) {
		$result = $query->getDevtypeList();		
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

	$app->post('/add-agent',function(Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addAgent($body);	
		if($result){
			return json_encode($body);
		}
	});	

	$app->post('/upload-devtype-image',function(Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->uploadDevtypeImage($body);	
		if($result){
			return json_encode($body);
		}
	});	

	$app->post('/upload-brand-image',function(Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->uploadBrandImage($body);	
		if($result){
			return json_encode($body);
		}
	});	

	$app->post('/upload-model-image',function(Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->uploadModelImage($body);	
		if($result){
			return json_encode($body);
		}
	});

	$app->post('/upload-network-image',function(Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->uploadNetworkImage($body);	
		if($result){
			return json_encode($body);
		}
	});	

	$app->get('/phone', function (Request $request, Response $response, array $args) use($query) {
		$result = $query->getModels();		
		return json_encode($result);
	});

	$app->get('/laptop-prices', function (Request $request, Response $response, array $args) use($query) {
	
		$result = $query->getLaptopPrices();		
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

	$app->get('/agents/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		if(count($params)){
		 $result = $query->getAgentsByPage($params);		
		}
		else{	
	
		$result = $query->getAgents();	
		}
		return json_encode($result);
	});

	$app->get('/logistics/', function (Request $request, Response $response, array $args) use($query) {
		$params = $request->getQueryParams();
		if(count($params)){
		 $result = $query->getLogisticsByPage($params);		
		}
		else{	
	
		$result = $query->getLogistics();	
		}
		return json_encode($result);
	});

	$app->get('/all-devices/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		if(count($params)){
		 	$result = $query->getAllModelsByPage($params);		
		}
		else{	
			$result = $query->getAllModels();	
		}
		return json_encode($result);
	});

	$app->get('/networks/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		if(count($params)){
		 	$result = $query->getNetworksByPage($params);		
		}
		else{	
			$result = $query->getNetworks();	
		}
		return json_encode($result);
	});

	$app->get('/devtypes/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		if(count($params)){
		 	$result = $query->getDevTypesByPage($params);		
		}
		else{	
			$result = $query->getDevtypes();	
		}
		return json_encode($result);
	});

	$app->get('/brands/', function (Request $request, Response $response, array $args) use($query) {
	
		$params = $request->getQueryParams();
		if(count($params)){
		 	$result = $query->getBrandsByPage($params);		
		}
		else{	
			$result = $query->getBrands();	
		}
		return json_encode($result);
	});

	$app->post('/',function(Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addBooking($body);	
		if($result){
			return json_encode($result);
		}
	});

	$app->post('/devices/', function (Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->getModels($body);	
		return json_encode($result);
	});

	$app->post('/consoles/', function (Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->getConsoles($body);	
		return json_encode($result);
	});

	$app->post('/add-devtype/', function (Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addDevtype($body);	
		return json_encode($result);
	});

	$app->post('/upload-devtype-image/', function (Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addDevtypeImage($body);	
		return json_encode($result);
	});

	$app->post('/add-model/', function (Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addModel($body);	
		return json_encode($result);
	});

	$app->post('/upload-model-image/', function (Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addModelImage($body);	
		return json_encode($result);
	});

	$app->post('/add-brand/', function (Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addBrand($body);	
		return json_encode($result);
	});

	$app->post('/upload-brand-image/', function (Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addBrandImage($body);	
		return json_encode($result);
	});

	$app->post('/add-network/', function (Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addNetwork($body);	
		return json_encode($result);
	});

	$app->post('/upload-network-image/', function (Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addNetworkImage($body);	
		return json_encode($result);
	});

	$app->post('/add-repair-options/', function (Request $request, Response $response, array $args) use($query) {
		$body = $request->getParsedBody();
		$result = $query->addRepairOptions($body);	
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

	$app->get('/invoice/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->getInvoice($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/agent/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->getAgent($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/devtype/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->getDevtype($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/brand/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->getBrand($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/network/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->getNetwork($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/stock/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->getStock($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/logistic/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->getLogistic($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/ticket/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->getTicket($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->get('/device/{id}', function (Request $request, Response $response, array $args) use($query) {
		$id = $args['id'];	
		$result = $query->getDevice($id);
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

	$app->put('/deactivate-agent/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->deactivateAgent($id);
		return json_encode($body);
	});

	$app->put('/activate-agent/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->activateAgent($id);
		return json_encode($body);
	});

	$app->put('/update-stock/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$body = $request->getParsedBody();
		$result = $query->updateStock($id,$body);
		return json_encode($body);
	});

	$app->put('/edit-price/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$body = $request->getParsedBody();
		$result = $query->updatePrice($id,$body);
		return json_encode($result);
	});

	$app->put('/update-agent/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$body = $request->getParsedBody();
		$result = $query->updateAgent($id,$body);
		return json_encode($result);
	});

	$app->put('/update-devtype/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$body = $request->getParsedBody();
		$result = $query->updateDevtype($id,$body);
		return json_encode($result);
	});

	$app->put('/update-brand/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$body = $request->getParsedBody();
		$result = $query->updateBrand($id,$body);
		return json_encode($result);
	});

	$app->put('/update-device/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$body = $request->getParsedBody();
		$result = $query->updateDevice($id,$body);
		return json_encode($result);
	});

	$app->put('/update-network/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$body = $request->getParsedBody();
		$result = $query->updateNetwork($id,$body);
		return json_encode($result);
	});

	$app->put('/edit-location/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$body = $request->getParsedBody();
		$result = $query->editLocation($id,$body);
		return json_encode($result);
	});

	$app->put('/edit-laptop-price/',function(Request $request, Response $response, array $args) use($query){
		$body = $request->getParsedBody();
		$result = $query->updateLaptopPrice($body);
		return json_encode($result);
	});

	$app->put('/lead-lost/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->leadLost($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/disable-model/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->disableModel($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/disable-brand/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->disableBrand($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/disable-network/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->disableNetwork($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/disable-type/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->disableType($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/enable-model/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->enableModel($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/enable-brand/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->enableBrand($id);
		if($result){
			return json_encode($result);
		}
	});

	$app->put('/enable-network/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->enableNetwork($id);
		if($result){
			return json_encode($result);
		}
	});

	
	$app->put('/enable-agent/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];	
		$result = $query->enableAgent($id);
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

	$app->delete('/delete-location/{id}',function(Request $request, Response $response, array $args) use($query){
		$id = $args['id'];
		$result = $query->deleteLocation($id);
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

