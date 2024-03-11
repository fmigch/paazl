<?php

$env = parse_ini_file('.env');

require('paazl/PaazlApi.php');
require('paazl/PaazlTransformer.php');
require('config/settings.php');

// incoming payload
$payload = '{
        "consigneeCountryCode": "NL",
        "consigneePostalCode": "7122TM",
        "shipmentParameters": {
          "goods": [
            {
              "height": 4,
              "length": 4,
              "price": 10,
              "quantity": 1,
              "volume": 1,
              "weight": 1,
              "width": 1
            }
          ],
          "numberOfGoods": 2,
          "totalPrice": 1,
          "totalVolume": 1,
          "totalWeight": 1
        }
    }';

$payload = json_decode($payload);

// load library for API request
$paazlApi = new PaazlApi(
	'TEST190220242',
	$env['PAAZL_APIKEY'],
	$env['PAAZL_APISECRET']
);

// load library for transformation to format needed for app
$paazlTransformer = new PaazlTransformer($language);

// add token to payload if needed
if (!isset($payload->token)) $payload->token = $paazlApi->getToken();

// merge payload and settings
$payload = json_encode((object)array_merge((array) json_decode($settings), (array) $payload));

// set headers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: *');
	http_response_code(200);
	return;
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// get data from Paazl API
if($endpoint == 'pickuplocations') {
	$data = $paazlApi->getPickupLocations($payload);
	$data = $paazlTransformer->getTransformedPickupLocations($data);
} else {
	$data = $paazlApi->getShippingOptions($payload);
  	$data = $paazlTransformer->getTransformedShippingOptions($data);
}

// transform and output
echo json_encode($data);