<?php

$env = parse_ini_file('.env');

require ('paazl/PaazlApi.php');
require ('paazl/PaazlTransformer.php');
require ('config/settings.php');

// set headers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: *');
	http_response_code(200);
	return;
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// get body
$payload = json_decode(file_get_contents('php://input'));

// load library for API request
$paazlApi = new PaazlApi(
	'TEST190220242',
	$env['PAAZL_APIKEY'],
	$env['PAAZL_APISECRET']
);

// load library for transformation to format needed for app
$paazlTransformer = new PaazlTransformer($language);

// add token to payload if needed
if (!isset($payload->token))
	$payload->token = $paazlApi->getToken();

// load settings
$settings = json_decode($settings);

// change sort model for pickuplocations
if ($endpoint == 'pickuplocations')
	$settings->sortingModel->orderBy = 'DISTANCE';

// merge payload and settings
$payload = json_encode((object) array_merge((array) $settings, (array) $payload));

// get data from Paazl API
if ($endpoint == 'pickuplocations') {
	$data = $paazlApi->getPickupLocations($payload);
	$data = $paazlTransformer->getTransformedPickupLocations($data);
} else {
	$data = $paazlApi->getShippingOptions($payload);
	$data = $paazlTransformer->getTransformedShippingOptions($data);
}

// transform and output
echo json_encode($data);
