<?php
$env = parse_ini_file('.env');

require('PaazlApi.php');
require('PaazlTransformer.php');
require('config/settings.php');

// incoming payload
$payload = '{
        "consigneeCountryCode": "NL",
        "consigneePostalCode": "7122TM",
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
          "numberOfGoods": 2,
          "totalPrice": 1,
          "totalVolume": 1,
          "totalWeight": 1
        }
    }';

// convert to object
$payload = json_decode($payload);
// object(stdClass)#6

// load library for Paazl API request
$paazlApi = new PaazlApi(
    'TEST190220242',
    $env['PAAZL_APIKEY'],
    $env['PAAZL_APISECRET']
);

// check if token is in payload
if (!isset($payload->token)) $payload->token = $paazlApi->getToken();

// Function to convert class of given object
function convertObjectClass($array, $final_class) {
    return unserialize(sprintf(
        'O:%d:"%s"%s',
        strlen($final_class),
        $final_class,
        strstr(serialize($array), ':')
    ));
}
 
$payload = convertObjectClass(array_merge((array) $payload, (array) $settings), 'stdClass');
$payload = json_encode($payload);

// headers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    http_response_code(200);
    return;
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// get data from Paazl API
$data = $paazlApi->getShippingOptions($payload, false);

// load library for transformation to format needed for app
$paazlTransformer = new PaazlTransformer('nl');

// transform and output
echo json_encode($paazlTransformer->getTransformedShippingOptions($data));
