<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require_once '../include/DBOps/DBConnection.php';


$conn= new DBConnection();
$db = $conn->mConnect();

$app = new \Slim\App;
$app->get('/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});
$app->get('/', function (Request $request, Response $response, array $args) {
  

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.getresponse.com/v3/contacts",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => '{"name" : "vincent9" , "email" : "vcdizon9@gmail.com", "campaign":{"campaignId": "agUXZ"}}',
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "x-auth-token: api-key 6ffd54dd5d25d89b9eb79b9120f3e3c8"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  return $response;
}


});
$app->run();