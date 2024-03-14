<?php

$language = 'nl_NL'; // en_US

$today = new DateTime('today');

$settings = '{
    "consigneeCountryCode": "NL",
    "consigneePostalCode": "7122TM",
    "currency": "EUR",
    "includeExternalDeliveryDates": false,
    "limit": 10,
    "locale": "' . $language . '",
    "numberOfProcessingDays": 0,
    "deliveryDateOptions": {
      "startDate": "' . $today->format('Y-m-d') . '",
      "numberOfDays": "8"
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
      "totalPrice": 1,
      "totalVolume": 1,
      "totalWeight": 1
    },
    "sortingModel": {
      "orderBy": "DATE",
      "sortOrder": "ASC"
    }
}';
