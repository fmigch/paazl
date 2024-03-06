<?php
$env = parse_ini_file('.env');

require('PaazlApi.php');
require('PaazlTransformer.php');

$paazlApi = new PaazlApi(
	'TEST190220242',
	$env['PAAZL_APIKEY'],
	$env['PAAZL_APISECRET']
);

$paazlTransformer = new PaazlTransformer('nl');

$payload = '{
        "consigneeCountryCode": "NL",
        "consigneePostalCode": "7122TM",
        "deliveryDateOptions": {
          "numberOfDays": 5
        },
        "currency": "EUR",
        "includeExternalDeliveryDates": false,
        "limit": 10,
        "locale": "nl_NL",
        "numberOfProcessingDays": 0,
        "deliveryDateOptions": {
          "startDate": "2024-02-21",
          "numberOfDays": "7"
        },
        "shipmentParameters": {
          "goods": [
            {
              "height": 1,
              "length": 1,
              "price": 10,
              "quantity": 1,
              "volume": 1,
              "weight": 1,
              "width": 1
            }
          ],
          "numberOfGoods": 1,
          "startMatrix": "A",
          "totalPrice": 10,
          "totalVolume": 1,
          "totalWeight": 1
        },
        "token": "' . $paazlApi->getToken() . '",
        "sortingModel": {
          "orderBy": "DATE",
          "sortOrder": "ASC"
        }
    }';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: *');
	http_response_code(200);
	return;
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$data = $paazlApi->getShippingOptions($payload, false);

echo json_encode($paazlTransformer->getTransformedShippingOptions($data));